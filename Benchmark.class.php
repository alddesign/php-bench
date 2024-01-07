<?php
declare(strict_types=1);

class Benchmark
{
	public $name = '';
	public $group = '';
	/** @var Closure */
	public $fn = null;
	public $error = '';

	public function __construct(string $name, string $group, callable $fn) 
	{
		$this->name = $name;
		$this->group = $group;
		$this->fn = $fn;
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

			$duration = round($end - $start, DECIMAL_PLACES);
			return $duration;
		}
		catch(Exception $ex)
		{
			$this->error = sprintf('Error while running benchmark "%s": %s.', $this->name, $ex->getMessage());
			return false;
		}		
	}

	/**
	 * @return float
	 */
	private function getTime()
	{
		return HRTIME_AVAILABLE ? hrtime(true) / 1e9 : microtime(true);
	}
}