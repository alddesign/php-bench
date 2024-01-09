<?php
declare(strict_types=1);

//Line length on the output
define('LINE_LEN_CLI', 80);
define('HTML_TPL_PATH', __DIR__ . '/template.html.php');

class Output
{
	/** @var string[] */
	public static $warnings = [];

	private function __construct() 
	{
		//Prevent making an object
	}

	public static function errorAndDie(string $message)
	{
		if(!IS_CLI)
		{
			@http_response_code(500);
		}

		echo $message;
		exit(1);
	}

	public static function addWarning(string $message)
	{
		self::$warnings[] = sprintf('Warning. %s', $message);
	}

	public static function result(BenchmarkHandler $handler)
	{
		if(!$handler->runComplete)
		{
			self::errorAndDie('Cannot print results. Please call BenchmarkHandler->run() first.');
		}

		if(IS_CLI)
		{
			if(ARGS['json'])
			{
				echo json_encode($handler->data);
			}
			else
			{
				self::resultCli($handler);
			}
		}
		else
		{
			if(ARGS['json'])
			{
				header('Content-Type: application/json');
				echo json_encode($handler->data);
			}
			else
			{
				require HTML_TPL_PATH;
			}
		}
	}
	private static function resultCli(BenchmarkHandler $handler)
	{
		$n = "\n";
		$output = '';
		
		//Title
		$output .= self::makeLineCli('','','-', '');
		$output .= self::makeLineCenterCli(TITLE . 'X', ' ');
		$output .= self::makeLineCli('','','-', '');
		$output .= $n;
		
		$output .= self::makeLineCli('### SYSTEM INFO ','','#','');
		foreach($handler->data['sysinfo'] as $name => $e)
		{
			$output .= self::makeLineCli($e['text'], $e['value'], '.', '.');
		}
		$output .= $n;


		$output .= self::makeLineCli('### BENCHMARK RESULTS ','','#','');
		foreach($handler->groups as $group)
		{
			$output .= '--- ' . $group . ' ---' . $n;
			foreach($handler->data['results'] as $name => $e)
			{
				if($e['group'] === $group)
				{
					$output .= self::makeLineCli(sprintf('[%s]%s', $e['status'], $name), $e['status'] === 'error' ? $e['error'] : number_format($e['time'], DECIMAL_PLACES), '.', '.');
				}
			}
		}
		$output .= $n;

		$output .= self::makeLineCli('### TOTALS ','','#', '');
		foreach($handler->data['totals'] as $name => $e)
		{
			$output .= self::makeLineCli($e['text'], number_format($e['value'], DECIMAL_PLACES), '.', '.');
		}

		echo $output;
	}

	private static function makeLineCli(string $start, string $end, string $padChar, string $pre)
	{
		$padLen = LINE_LEN_CLI - strlen($start . $pre) - strlen($end);
		return $start . $pre . str_pad('', $padLen, $padChar) . $end . "\n";
	}

	private static function makeLineCenterCli(string $center, $padChar = '.')
	{
		$padLen = (int)((LINE_LEN_CLI - strlen($center)) / 2);
		$line = str_pad('', $padLen, $padChar) . $center . str_pad('', $padLen, $padChar);
		$line .= strlen($line) < LINE_LEN_CLI ? $padChar . "\n" : "\n";
		return $line;
	}
}