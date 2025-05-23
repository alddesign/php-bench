<?php

$opStatus = function_exists('opcache_get_status') ? opcache_get_status() : false;

//Make sure to return an array
return 
[
	'sysinfo' => 
	[
		'php_bench_version' => 
		[
			'text' => 'php-bench version',
			'value' => PHP_BENCH_VERSION
		],
		'php_version' => 
		[
			'text' => 'PHP Version', 
			'value' => PHP_VERSION
		],
		'php_platform' => 
		[
			'text' => 'PHP Platform', 
			'value' => PHP_OS
		],
		'php_server_interface' => 
		[
			'text' => 'PHP server interface', 
			'value' => PHP_SAPI
		],
		'server_os_family' => 
		[
			'text' => 'Server OS Family', 
			'value' => defined('PHP_OS_FAMILY') ? PHP_OS_FAMILY : '[undefined]'
		],
		'server_os' => 
		[
			'text' => 'Server OS', 
			'value' => php_uname('s')
		],
		'server_architecture' => 
		[
			'text' => 'Server Architecture', 
			'value' => php_uname('m')
		],
		'cpu_model' => 
		[
			'text' => 'CPU Model', 
			'value' => Helper::getCpuModel()
		],
		'host' => 
		[
			'text' => 'Host', 
			'value' => IS_CLI ? gethostname() : ($_SERVER['SERVER_NAME'] ?? 'null') . '@' . ($_SERVER['SERVER_ADDR'] ?? 'null')
		],
		'php_memory_limit' => 
		[
			'text' => 'PHP memory_limit', 
			'value' => ini_get('memory_limit')
		],
		'php_max_execution_time' => 
		[
			'text' => 'PHP max_execution_time', 
			'value' => ini_get('max_execution_time')
		],
		'opcache_status' => 
		[
			'text' => 'OPCache status', 
			'value' => is_array($opStatus) && @$opStatus['opcache_enabled'] ? 'enabled' : 'disabled'
		],
		'opcache_jit' => 
		[
			'text' => 'OPCache JIT', 
			'value' => is_array($opStatus) && @$opStatus['jit']['enabled'] ? 'enabled' : 'disabled/unavailable'
		],
		'pcre_jit' => 
		[
			'text' => 'PCRE JIT', 
			'value' => @ini_get('pcre.jit') ? 'enabled' : 'disabled'
		],
		'xdebug_extension' => 
		[
			'text' => 'XDebug extension', 
			'value' => extension_loaded('xdebug') ? 'enabled' : 'disabled'
		],
		'supplied_args' => 
		[
			'text' => 'Supplied args', 
			'value' => Helper::formatArgsArray(SUPPLIED_ARGS)
		],
		/*
		'all_args' => 
		[
			'text' => 'All args', 
			'value' => IS_CLI ? json_encode(ARGS) : json_encode(ARGS, JSON_PRETTY_PRINT)
		],
		*/
		'benchmark_started' => 
		[
			'text' => 'Benchmark started', 
			'value' => (new DateTime('now'))->format('Y-m-d H:i:s')
		]
	],
	'results' => [],
	'total_time' => 0.0
	
	/**
	 * Removed:
	 * I dont think we need an entire section for totals, because there only two of them.
	 * peak_memory_usage is kinda usless because:
	 * - The vars outside the benchmarks have a way larger memory footprint than the benchmarks themselves, and we cannot separate them
	 * - The peak_memory_usage will always be the same (2 or 4M)
	 * - It doesnt say shit about anything
	 */
	/*
	'totals' => 
	[
		'total_time' =>
		[
			'text' => 'Total time (sec.)',
			'value' => 0.0
		],
		'peak_memory_usage' =>
		[
			'text' => 'Peak memory usage (MiB)',
			'value' => 0.0
		],
	],
	*/
];
