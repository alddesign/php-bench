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
	public $groups = [];
	public $runComplete = false;

	public function __construct() 
	{
		$this->benchmarks = require BENCHMARKS_FILE_PATH;
		$this->data = require DATA_FILE_PATH;

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

	public function runBenchmarks()
	{
		$this->data['results'] = [];
		$totalTime = 0.0;

		foreach($this->benchmarks as $benchmark)
		{
			//Check whether to run a certain benchmark based on the args
			$runBenchmark = empty(ARGS['benchmarks']) || in_array($benchmark->name, ARGS['benchmarks'], true);
			$runGroup = empty(ARGS['groups']) || in_array($benchmark->group, ARGS['groups'], true);
			
			$result =
			[
				'group' => $benchmark->group,
				'status' => '', //ok,skipped,error
				'error' => '',
				'time' => 0.0,
			];

			if($runBenchmark && $runGroup)
			{
				//Running the benchmark!
				$time = $benchmark->run();
				if($time !== false)
				{
					$result['status'] = 'ok';
					$result['time'] = round($time, DECIMAL_PLACES);
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

		$this->data['totals']['total_time']['value'] = round($totalTime, DECIMAL_PLACES);
		$this->data['totals']['peak_memory_usage']['value'] = round(memory_get_peak_usage(true) / 1024 / 1024, DECIMAL_PLACES);

		$this->runComplete = true;
	}
}