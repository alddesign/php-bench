<?php
declare(strict_types=1);

class Helper
{
	private function __construct() 
	{

	}

	/**
	 * Trying to set max_execution_time as high as possible. Values to try are definded in TIME_LIMITS_TO_TRY
	 * 
	 * @param int[] $times
	 */
	public static function trySetTimeLimits()
	{
		$current = (int)ini_get('max_execution_time');
		$unable = [];
		foreach(TIME_LIMITS_TO_TRY as $time)
		{
			if($current > 0 && ($current < $time || $time === 0))
			{
				if(@ini_set('max_execution_time', (string)$time) !== false) //set_time_limit() always return false when xdebug is active
				{
					break; //Good
				}
				else
				{
					$unable[] = $time;
				}
			}
		}

		if(!empty($unable))
		{
			$unable = implode(',', $unable);
			$current = (int)ini_get('max_execution_time');
			$warning = '';
			$warning .= "Unable tot increase max_execution_time to one of the following values.: $unable. Current value: $current. ";
			$warning .= 'This can be due to your apache/php config, or limited by your hosting provider. ';
			$warning .= 'Depending on your systems performance, this is necessary to run all benchmarks.';
			
			Output::warning($warning);
		}
	}

	/**
	 * Gets the arguments from either the cli or the query string
	 * @return array
	 */
	public static function getArgs()
	{
		$args = [];

		if (IS_CLI) 
		{
			//Strip the '--'
			$cleanedArgs = array_map(function ($arg) 
			{
				return strpos($arg, '--') !== 0 ? null : str_replace('--', '', $arg);
			}, $GLOBALS['argv']);

			//Remove null elements and convert to key value array
			parse_str(implode('&', array_filter($cleanedArgs)), $args);
		} 
		else 
		{
			parse_str($_SERVER['QUERY_STRING'], $args);
		}

		//Merge params
		$args = array_merge(DEFAULT_ARGS, $args);

		//Convert some specific args to array
		$args['groups'] = !empty($args['groups']) ? explode(',', (string)$args['groups']) : [];
		$args['benchmarks'] = !empty($args['benchmarks']) ? explode(',', (string)$args['benchmarks']) : [];

		if(!empty($args['benchmarks']) && !empty($args['groups']))
		{
			Output::error('Invalid args. Please either the "groups" or "benchmarks" arg, not both.');
		}

		//Cast the type of $args to the type defined in DEFAULT_ARGS
		foreach (DEFAULT_ARGS as $key => $value) 
		{
			if (isset($args[$key])) 
			{
				settype($args[$key], gettype($value));
			}
		}

		return $args;
	}
}