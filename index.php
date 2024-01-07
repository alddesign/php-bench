<?php
//Configure error reporting to get all errors
error_reporting(E_ALL);
@ini_set('display_errors', '1');
@ini_set('display_startup_errors', '1');
//Check PHP Version
if(PHP_MAJOR_VERSION < 7){ echo 'This script requires PHP 7.0 or higher.'; exit(1);}

//Define important constants
define('PHP_BENCH_VERSION', '1.0');
define('TITLE', 'alddesign/php-bench ' . PHP_BENCH_VERSION);
define('DECIMAL_PLACES', 3);
define('DEFAULT_ARGS', ['benchmarks' => [], 'groups' => [], 'multiplier' => 1.0]);
define('TIME_LIMITS_TO_TRY', [0,300,240,200,180,160,140,120,100,80,60,40,30]);
/** @var bool */
define('HRTIME_AVAILABLE', function_exists('hrtime') && hrtime(true) !== false);
/** @var bool */
define('IS_CLI', PHP_SAPI === 'cli');
define('EOL', IS_CLI ? PHP_EOL : '<br>');

//Loading classes
require './Output.class.php';
require './Helper.class.php';
require './BenchmarkHandler.class.php';
require './Benchmark.class.php';

//Try to set the max_execution_time as high as possible
Helper::trySetTimeLimits();

//Get Args from command line of query string
/** @var array */
define('ARGS', Helper::getArgs());
/** @var float */
define('MULTIPLIER', (float)ARGS['multiplier']);


//Running the Benchmarks
$benchmarkHandler = new BenchmarkHandler();
$benchmarkHandler->runBenchmarks();

//Show the results
Output::data($benchmarkHandler);
