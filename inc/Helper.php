<?php
declare(strict_types=1);

abstract class Helper
{
	private static $_args = [];

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
			$warning = <<<msg
			Unable to increase PHP max_execution_time. Tried following values: $unable.
			Current value: $current.
			This can be due to your apache/php config, or be limited by your hosting provider.
			Depending on your systems performance, this is necessary to run all benchmarks.
			If there is no output, try to set the "multiplier" argument to a lower value.
			msg;
			
			Output::warning($warning);
		}
	}

	/**
	 * Get the arguments
	 * 
	 * @param int $i For 0 = final arguments (all), 1 = supplied arguments (by the user)
	 * 
	 * @return array
	 */
	public static function getArgs(int $i)
	{
		//make sure to load parse them only once
		if(empty(self::$_args))
		{
			self::$_args = self::parseArgs();
		}

		return self::$_args[$i] ?? [];
	}

	/**
	 * Gets the arguments from either the cli or the query string
	 * @return array [0] => finalArags, [1] => suppliedArgs
	 */
	private static function parseArgs()
	{
		//Get the args
		$inputArgs = [];
		if (IS_CLI) 
		{
			//Strip the '--'
			$cleanedArgs = array_map(function ($arg) 
			{
				return strpos($arg, '--') !== 0 ? null : str_replace('--', '', $arg);
			}, $GLOBALS['argv']);

			//Remove null elements and convert to key value array
			parse_str(implode('&', array_filter($cleanedArgs)), $inputArgs);
		} 
		else 
		{
			parse_str($_SERVER['QUERY_STRING'], $inputArgs);
		}

		//Merge args: if an arg is supplied, take this value, otherwise the default, discard the rest
		//Plus: Casting the input args to the type definded in DEFAULT_ARGS
		$finalArgs = []; //The merged args
		$suppliedArgs = []; //The (valid) args supplie by the user
		foreach(DEFAULT_ARGS as $k => $v)
		{
			$type = gettype($v);
			if(isset($inputArgs[$k]))
			{
				$vi = $inputArgs[$k];
				settype($vi, gettype($v));
				$finalArgs[$k] = $vi;
				$suppliedArgs[$k] = $vi;
			}
			else
			{
				$finalArgs[$k] = $v;
			}
		}

		//Sanity check
		if(!empty($finalArgs['benchmarks']) && !empty($finalArgs['groups']))
		{
			Output::errorAndDie('Invalid args. Please supply either the "groups" or "benchmarks" arg, not both.');
		}

		return [$finalArgs, $suppliedArgs];
	}

	public static function getScriptUrl()
	{
		$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';

		return sprintf('%s://%s%s', $protocol, $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI']);
	}

	/**
	 * @return string
	 */
	public static function argsToCliArgs(array $args)
	{
		if(empty($args))
		{
			return '';
		}

		$cliArgs = '';
		foreach($args as $key => $value)
		{
			$cliArgs .= sprintf('--%s=%s' . ' ', $key, $value);
		}
		
		return substr($cliArgs, 0, strlen($cliArgs) - 1); //remove the last space
	}

	/**
	 * Sends an async HTTP GET request without waiting for the result.
	 * @param string $url
	 * @return void
	 */
	public static function asyncHttpGET(string $url) 
	{
		$context = stream_context_create(['http' => ['method' => 'GET', 'header' => "Connection: close\r\n", 'timeout' => 0]]);
		
		//Timout=0 will emitt a warning here, so the debugger will stop, but the request is being made!
		@file_get_contents($url, false, $context);
	}

	public static function openSession(string $sid)
	{
		//Close existing session, just in case...
		self::closeSession();

		session_id($sid);
		session_start();

		//Prevent PHP from sending fucking cookie headers (protect the wss session id)
		header_remove('Set-Cookie');
	}

	public static function closeSession()
	{
		if(session_status() === PHP_SESSION_ACTIVE)
		{
			session_write_close();
		}
	}

	/**
	 * Tries to create a temp file with unique name, using several methods. 
	 * We need to make sure multiple threads dont interact interact with each other.
	 * And we need to find a location where we can read/write.
	 * 
	 * @return string Path to the file
	 */
	public static function makeTempFile()
	{
		//Try the local ./tmp directory
		$path = @tempnam(__DIR__ . '/tmp', 'php-bench_');
		if($path !== false)
		{
			return $path;
		}

		//Tying the systems temp dir
		$path = @tempnam(sys_get_temp_dir(), 'php-bench_');
		if($path !== false)
		{
			return $path;
		}

		throw new Exception('Unabled to create temp file. No permissions?');
	}

	/** 
	 * Formats args as a string in CLI or URL format
	 */
	public static function formatArgsArray(array $argsArray)
	{
		$s = '';
		$f = true;
		foreach($argsArray as $k => $v)
		{
			$s .= IS_CLI ? sprintf('%s--%s=%s', $f ? '' : ' ', $k, $v) : sprintf('%s%s=%s', $f ? '?' : '&', $k, $v);
			$f = false;
		}

		return $s;
	}

	public static function getCpuModel()
	{
		if (IS_WINDOWS) 
		{
			@exec('wmic cpu get Name', $output);
			if(isset($output[1]))
			{
				return trim($output[1]);
			}
		} 
		else 
		{
			$cpuinfo = @file_get_contents('/proc/cpuinfo');
			if($cpuinfo) 
			{
				$lines = explode("\n", $cpuinfo);
				foreach ($lines as $l) 
				{
					$ll = mb_strtolower($l);
					if (str_starts_with($ll, 'model')) 
					{
						return trim(substr($l, strpos($l, ':') + 1)); 
					}
				}
			}
		}

		return '[unknown]';
	}

	private static function shuffle_assoc(&$array) {
		$keys = array_keys($array);
	
		shuffle($keys);
	
		foreach($keys as $key) {
			$new[$key] = $array[$key];
		}
	
		$array = $new;
	
		return true;
	}

	private static function r($b = 6)
	{
		return bin2hex(openssl_random_pseudo_bytes($b));
	}
	
	public static function makeArrayTestsAssoc(int $size)
	{
		echo '<pre>';
		$a = [];
		for($c = 0; $c < $size; $c++)
		{
			$a[self::r()] = self::r();
		}
		
		echo "\n" . '//initialize' . "\n";
		$h = '$a = [';
		foreach($a as $k => $v)
		{
			$h .= sprintf('\'%s\'=>\'%s\',', $k, $v);
		}
		$h .= '];';
		echo $h . "\n\n";

		self::shuffle_assoc($a);
		echo "\n" . '//add elements via assoc index' . "\n";
		foreach($a as $k => $v)
		{
			echo sprintf('$a[\'%s\'] = \'%s\';%s', self::r(), self::r(), "\n");
		}

		self::shuffle_assoc($a);
		echo "\n" . '//add elements blunt' . "\n";
		foreach($a as $k => $v)
		{
			echo sprintf('$a[] = \'%s\';%s', self::r(), "\n");
		}

		self::shuffle_assoc($a);
		echo "\n" . '//access random index and setting values' . "\n";
		foreach($a as $k => $v)
		{
			echo sprintf('$a[\'%s\'] = \'%s\';%s', $k, self::r(), "\n");
		}

		self::shuffle_assoc($a);
		echo "\n" . '//isset on existing index' . "\n";
		foreach($a as $k => $v)
		{
			echo sprintf('isset($a[\'%s\']);%s', $k, "\n");
		}

		self::shuffle_assoc($a);
		echo "\n" . '//isset on non existing index' . "\n";
		foreach($a as $k => $v)
		{
			echo sprintf('isset($a[\'%s\']);%s', self::r(), "\n");
		}

		self::shuffle_assoc($a);
		echo "\n" . '//unset elements' . "\n";
		foreach($a as $k => $v)
		{
			echo sprintf('unset($a[\'%s\']);%s', $k, "\n");
		}

		echo "\n" . '//unset the array' . "\n";
		echo 'unset($a);' . "\n";
		echo '</pre>';
		die;
	}

	public static function makeArrayTests(int $size)
	{
		echo '<pre>';
		$a = [];
		for($c = 0; $c < $size; $c++)
		{
			$a[] = self::r();
		}
		$ks = array_keys($a);
		
		echo "\n" . '//initialize' . "\n";
		$h = '$a = [';
		foreach($a as $k => $v)
		{
			$h .= sprintf('\'%s\',', $v);
		}
		$h .= '];';
		echo $h . "\n\n";

		echo "\n" . '//add elements via index' . "\n";
		shuffle($ks);
		foreach($ks as $k)
		{
			echo sprintf('$a[%s] = \'%s\';%s', $k + $size, self::r(), "\n");
		}

		echo "\n" . '//add elements blunt' . "\n";
		for($i = 0; $i < $size; $i++)
		{
			echo sprintf('$a[] = \'%s\';%s', self::r(), "\n");
		}

		shuffle($a);
		echo "\n" . '//access random index and setting values' . "\n";
		shuffle($ks);
		foreach($ks as $k)
		{
			echo sprintf('$a[%s] = \'%s\';%s', $k, self::r(), "\n");
		}

		shuffle($a);
		echo "\n" . '//isset on existing index' . "\n";
		shuffle($ks);
		foreach($ks as $k)
		{
			echo sprintf('isset($a[%s]);%s', $k, "\n");
		}

		shuffle($a);
		echo "\n" . '//isset on non existing index' . "\n";
		shuffle($ks);
		foreach($ks as $k)
		{
			echo sprintf('isset($a[%s]);%s', $k + ($size * 3), "\n");
		}

		shuffle($a);
		echo "\n" . '//unset elements' . "\n";
		shuffle($ks);
		foreach($ks as $k)
		{
			echo sprintf('unset($a[%s]);%s', $k, "\n");
		}

		echo "\n" . '//unset the array' . "\n";
		echo 'unset($a);' . "\n";
		echo '</pre>';
		die;
	}
}