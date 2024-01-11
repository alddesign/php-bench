<?php
declare(strict_types=1);

class Benchmark
{
	public $name = '';
	public $group = '';
	/** @var Closure */
	public $fn = null;
	public $error = '';
	/** @var bool */
	private $hrTimeAvailable = false;

	public function __construct(string $name, string $group, callable $fn) 
	{
		$this->name = $name;
		$this->group = $group;
		$this->fn = $fn;
		$this->hrTimeAvailable = function_exists('hrtime') && hrtime(true) !== false;
	}

	/**
	 * @return float|false The duration of the test in seconds or FALSE on error.
	 */
	public function run()
	{
		try
		{
			$start = $this->getTime();
			$this->fn->__invoke();
			$end = $this->getTime();

			$duration = $end - $start;
			return $duration;
		}
		catch(Exception $ex)
		{
			$this->error = $ex->getMessage();
			return false;
		}		
	}

	/**
	 * @return float
	 */
	private function getTime()
	{
		return $this->hrTimeAvailable ? hrtime(true) / 1e9 : microtime(true);
	}
}