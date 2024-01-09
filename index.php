<?php
//Configure error reporting to get all errors
error_reporting(E_ALL);
@ini_set('display_errors', '1');
@ini_set('display_startup_errors', '1');
/** @var bool */
define('IS_CLI', PHP_SAPI === 'cli');

checkPhpVersion(7,0);

//Loading classes
require __DIR__ . '/Output.class.php';
require __DIR__ . '/Helper.class.php';
require __DIR__ . '/BenchmarkHandler.class.php';
require __DIR__ . '/Benchmark.class.php';

//Define important constants
define('PHP_BENCH_VERSION', '1.0');
define('TITLE', 'alddesign/php-bench ' . PHP_BENCH_VERSION);
define('DECIMAL_PLACES', 3);
define('DEFAULT_ARGS', ['benchmarks' => [], 'groups' => [], 'multiplier' => 1.0, 'json' => false]);
define('TIME_LIMITS_TO_TRY', [0,300,240,200,180,160,140,120,100,80,60,40,30]);
/** @var bool */
define('HRTIME_AVAILABLE', function_exists('hrtime') && hrtime(true) !== false);
/** @var array */
define('ARGS', Helper::getArgs());
/** @var float */
define('MULTIPLIER', (float)ARGS['multiplier']);

//Try to set the max_execution_time as high as possible
Helper::trySetTimeLimits();

//Running the Benchmarks
$benchmarkHandler = new BenchmarkHandler();
$benchmarkHandler->runBenchmarks();

//Show the results
Output::result($benchmarkHandler);

function checkPhpVersion($majorRequired, $minorReq)
{
    if(PHP_MAJOR_VERSION < $majorRequired || (PHP_MINOR_VERSION < $minorReq && PHP_MAJOR_VERSION === $majorRequired))
    {
        if(!IS_CLI)
        {
            @http_response_code(500);
        }
        echo 'This script requires PHP 7.0 or higher.';
        exit(1);
    }
}