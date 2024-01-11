<?php
declare(strict_types=1);

define('BENCHMARKS_FILE_PATH', __DIR__ . '/benchmarks.php');
define('DATA_FILE_PATH', __DIR__ . '/data.php');

class BenchmarkHandler
{
	/** @var Benchmark[] */
	public $benchmarks;
	/** @var array */
	public $data = [];
	/** @var array Data from the individual threads */
	public $threadsData = [];
	/** @var string[] */
	public $groups = [];

	private $groupFilter = [];
	private $benchmarkFilter = [];

	private int $threadId = -1;
	private bool $isThread = false;
	private bool $isMerged = false;
	private bool $done = false;
	private string $masterSid = '';

	public function __construct(string $groupsFilter, string $benchmarksFilter, int $threadId, string $masterSid) 
	{
		$this->benchmarks = require BENCHMARKS_FILE_PATH;
		$this->data = require DATA_FILE_PATH;
		$this->threadId = $threadId;
		$this->isThread = $threadId >= 0;
		$this->masterSid = $masterSid;
		$this->groupFilter = empty($groupsFilter) ? [] : explode(',', $groupsFilter);
		$this->benchmarkFilter = empty($benchmarksFilter) ? [] : explode(',', $benchmarksFilter);

		//Build array of all groups - needed to group output
		$this->groups = [];
		foreach($this->benchmarks as $benchmark)
		{
			if(!in_array($benchmark->group, $this->groups, true))
			{
				$this->groups[] = $benchmark->group;
			}
		}
	}

	/**
	 * Merges data from all threads and creates new, finished benchmark Handler based on that.
	 * Note that every thread does the full benchmark.
	 * So the final time gets calculated like this: averagerage time of all threads divided by the number of threads.
	 * Example: 4 threads, each thread need 2sec.
	 * So time = ((2+2+2+2)/4)/4 = 0.5 sec. 
	 * 
	 * @return BenchmarkHandler
	 */
	public static function fromThredsData(array $threadsData)
	{
		$l = count($threadsData);

		$handler = new BenchmarkHandler('','', -1, '');
		$handler->isMerged = true;
		$handler->done = true;
		$handler->threadsData = $threadsData;

		//Using thread 0 as the reference to loop through results
		$totalTime = 0.0;
		foreach($threadsData[0]['data']['results'] as $name => $e)
		{
			$time = 0.0;
			$handler->data['results'][$name] = $e;
			$handler->data['results'][$name]['status'] = 'ok';
			for($i = 0; $i < $l; $i++)
			{
				$e2 = $threadsData[$i]['data']['results'][$name];
				if($e2['status'] === 'ok')
				{
					$time += $e2['time'];
				}
				if($e2['status'] === 'skipped')
				{
					$time = 0.0;
					$handler->data['results'][$name]['status'] = 'skipped';
					break;
				}
				if($e2['status'] === 'error')
				{
					$time = 0.0;
					$handler->data['results'][$name]['status'] = 'error';
					$handler->data['results'][$name]['error'] = $e2['error'];
					break; //Im pretty sure an error would occour always
				}

				//Final thred, time to update
				if($i === $l - 1)
				{
					$handler->data['results'][$name]['time'] = $time / ($l * $l);
					$totalTime += $time / ($l * $l);
				}
			}
		}

		$handler->data['total_time'] = $totalTime;
		
		return $handler;
	}

	public function runBenchmarks()
	{
		//Don try to re
		if($this->done || $this->isMerged)
		{
			Output::errorAndDie('Dont re-run a BanchmarkHandler');
		}

		if($this->isThread)
		{
			Helper::openSession($this->masterSid);
			$_SESSION['threads'][$this->threadId]['status'] = 'running';
			Helper::closeSession();
		}

		$this->data['results'] = [];
		$totalTime = 0.0;

		foreach($this->benchmarks as $benchmark)
		{
			//Check whether to run a certain benchmark based on the args
			$runThisBenchmark = empty($this->benchmarkFilter) || in_array($benchmark->name, $this->benchmarkFilter, true);
			$runThisGroup = empty($this->groupFilter) || in_array($benchmark->group, $this->groupFilter, true);
			
			$result =
			[
				'group' => $benchmark->group,
				'status' => '', //ok,skipped,error
				'error' => '',
				'time' => 0.0,
			];

			if($runThisBenchmark && $runThisGroup)
			{
				//Running the benchmark!
				$time = $benchmark->run();
				if($time !== false)
				{
					$result['status'] = 'ok';
					$result['time'] = $time;
					$totalTime += $time;
				}
				else
				{
					$result['status'] = 'error';
					$result['error'] = $benchmark->error;
				}
			}
			else
			{
				$result['status'] = 'skipped';
			}

			//Add the result
			$this->data['results'][$benchmark->name] = $result;
			
		}

		$this->data['total_time'] = $totalTime;
		$this->done = true;
	}
}