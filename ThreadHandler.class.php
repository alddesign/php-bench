<?php
declare(strict_types=1);


class ThreadHandler
{
	/** @var int The number of threads */
	private int $no = 0;
	private $sid = '';

	private const CHECK_INTERVAL = 4; //Seconds

	public function __construct() 
	{
		$this->no = ARGS['threads'];
		$this->sid = sprintf('%s-%s', 'php-bench', bin2hex(random_bytes(10))); //Generate a random unique session id
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
				'error' => '',
				'warnings' => [],
				'data' => []
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
				$cmd = sprintf('START /B php "%s" %s > NUL 2> NUL', $indexPhpPath, Helper::argsToCliArgs($args));

				$shit = shell_exec($cmd);
				$x = 1;
			}
			else
			{
				$url = Helper::getScriptUrl() . '?' . http_build_query($args);
				Helper::asyncGet($url);
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

		Helper::openSession($this->sid);
		for($i = 0; $i < $this->no; $i++)
		{
			if(isset($_SESSION['threads'][$i]))
			{
				if($_SESSION['threads'][$i]['status'] === 'running' || $_SESSION['threads'][$i]['status'] === 'ready')
				{
					$allFinished = false;
				}
			}
		}

		if($allFinished)
		{
			$data = $_SESSION['threads'];
			session_destroy();

			return $data;
		}

		Helper::closeSession();
		return false;
	}
}