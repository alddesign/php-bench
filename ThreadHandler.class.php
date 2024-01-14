<?php
declare(strict_types=1);


class ThreadHandler
{
	/** @var int The number of threads */
	private $no = 0;
	private $sid = '';
	private $isWindows = false;

	private const CHECK_INTERVAL = 4; //Seconds
	private const WIN_CMD = 'START /B php "%s" %s > NUL 2> NUL';
	private const LNX_CMD = 'php "%s" %s > /dev/null 2> /dev/null &';
	/** @var int After no heartbeat update after this amount of time, a thread is considered dead. You might increase that number if your your system is slow. */
	private const THREAD_TIMEOUT = ARGS['thread_timeout'] * MULTIPLIER; 

	public function __construct() 
	{
		if(session_status() === PHP_SESSION_DISABLED)
		{
			output::errorAndDie('Cannot run multithreaded benchmark. PHP sessions are disabled. Sessions are needed for thread synchronization.');
		}

		$this->no = ARGS['threads'];
		$this->sid = sprintf('%s-%s', 'm', bin2hex(random_bytes(10))); //Generate a random unique session id
		$this->isWindows = strtolower(substr(PHP_OS, 0, 3)) === 'win';
	}

	/** @return array */
	public function runThreads()
	{
		//Initializing the master session
		Helper::openSession($this->sid);
		for($i = 0; $i < $this->no; $i++)
		{
			//The top level keys of $_SESSIONmust be string, therefore we need the 'threads' key
			$_SESSION['threads'][$i] =
			[
				'status' => 'ready', //ready,running,error
				'data' => [],
				'heartbeat' => 0
			];
		}
		Helper::closeSession();

		//Starting threads via HTTP GET request
		for($i = 0; $i < $this->no; $i++)
		{
			$args = ARGS;
			$args['threads'] = 0;
			$args['thread_id'] = $i;
			$args['master_sid'] = $this->sid;
			if(IS_CLI)
			{
				
				$indexPhpPath = __DIR__ . DIRECTORY_SEPARATOR . 'index.php';
				$cmd = $this->isWindows ? self::WIN_CMD : self::LNX_CMD;
				$cmd = sprintf($cmd, $indexPhpPath, Helper::argsToCliArgs($args));

				shell_exec($cmd);
			}
			else
			{
				$url = Helper::getScriptUrl() . '?' . http_build_query($args);
				Helper::asyncHttpGET($url);
			}
		}

		//Wait...
		$data = false;
		while(!$data)
		{
			sleep(self::CHECK_INTERVAL);
			$data = $this->checkStatus();
		}

		return $data;
	}

	/**
	 * @return array|false The thread data if all threads finished, FALSE threads still running
	 */
	private function checkStatus()
	{
		$allFinished = true;
		$deadTime = time() - self::THREAD_TIMEOUT;

		Helper::openSession($this->sid);
		$data = $_SESSION['threads'];
		Helper::closeSession();
		for($i = 0; $i < $this->no; $i++)
		{
			if($data[$i]['heartbeat'] < $deadTime && $data[$i]['status'] !== 'done')
			{
				Output::errorAndDie(sprintf('Thread timeout (%s sec.) exceeded by thread %s. If your system is very slow, please increse this timeout by using the "thread_timeout" argument.', self::THREAD_TIMEOUT, $i));
			}

			if($data[$i]['status'] === 'running' || $data[$i]['status'] === 'ready')
			{
				$allFinished = false;
			}
		}

		if($allFinished)
		{
			Helper::openSession($this->sid);
			session_destroy();

			return $data;
		}
		return false;
	}
}