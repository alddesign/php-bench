<?php
declare(strict_types=1);

class Benchmark
{
	/** @var string The unique name of the benchmark */
	public $name = '';
	/** @var string The name of the group the benchmark belongs to. Each group has its own section in the output. */
	public $group = '';
	/** @var Closure The function with the benchmark code */
	private $benchmarkFn = null;
	/** @var Closure The function which gets executed before the benchmark starts. Its execution time will not be added to the result */
	private $preFn = null;
	/** @var Closure The function which gets executed after the benchmark has finised. Its execution time will not be added to the result */
	private $postFn = null;
	/** @var array Used to exchange data between benchmarkFn, preFn and PostFn */
	private $data = [];
	public $error = '';
	/** @var bool */
	private $hrTimeAvailable = false;

	/**
	 * @param string $name The unique name of the benchmark
	 * @param string $group The name of the group the benchmark belongs to. Each group has its own section in the output.
	 * @param callable $benchmarkFn The function which contains the actual benchmark code
	 * @param callable|null $preFn (optional) The function which gets executed before the benchmark starts. Its execution time will not be added to the result
	 * @param callable|null $postFn (optional) The function which gets executed after the benchmark has finised. Its execution time will not be added to the result
	 */
	public function __construct(string $name, string $group, callable $benchmarkFn, ?callable $preFn = null, ?callable $postFn = null) 
	{
		$this->name = $name;
		$this->group = $group;
		$this->benchmarkFn = $benchmarkFn;
		$this->preFn = $preFn;
		$this->postFn = $postFn;
		$this->hrTimeAvailable = function_exists('hrtime') && hrtime(true) !== false;
	}

	/**
	 * @return float|false The duration of the test in seconds or FALSE on error.
	 */
	public function run()
	{
		$duration = 0.0;
		$this->data = []; //Make sure there is no leftover data from previous benchmarks.

		//preFn
		try
		{
			if($this->preFn !== null)
			{
				$this->preFn->__invoke();
			}
		}
		catch(Exception $ex)
		{
			$this->error = '[pre]:' . $ex->getMessage();
			return false;
		}

		//Benchmark fn
		try
		{
			$start = $this->getTime();
			$this->benchmarkFn->__invoke();
			$end = $this->getTime();
			$duration = $end - $start;
		}
		catch(Exception $ex)
		{
			$this->error = $ex->getMessage();
			return false;
		}		
		
		//postFn
		try
		{
			if($this->postFn !== null)
			{
				$this->postFn->__invoke();
			}
		}
		catch(Exception $ex)
		{
			$this->error = '[post]:' . $ex->getMessage();
			return false;
		}


		return $duration;
	}

	/**
	 * @return float
	 */
	private function getTime()
	{
		return $this->hrTimeAvailable ? hrtime(true) / 1e9 : microtime(true);
	}
}