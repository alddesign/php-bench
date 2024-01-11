<?php
//Configure error reporting to get all errors
error_reporting(E_ALL);
@ini_set('display_errors', '1');
@ini_set('display_startup_errors', '1');

checkPhpVersion(7,0);

//Loading classes
require __DIR__ . '/Output.class.php';
require __DIR__ . '/Helper.class.php';
require __DIR__ . '/BenchmarkHandler.class.php';
require __DIR__ . '/Benchmark.class.php';
require __DIR__ . '/ThreadHandler.class.php';

//Define important constants
define('PHP_BENCH_VERSION', 'v1.0.0-beta.1');
define('TITLE', 'alddesign/php-bench ' . PHP_BENCH_VERSION);
define('DECIMAL_PLACES', 3);
define('DEFAULT_ARGS', 
    [
        'benchmarks' => '', 
        'groups' => '', 
        'multiplier' => 1.0, 
        'json' => false, 
        'threads' => 0,
        'thread_id' => -1,
        'master_sid' => '',
    ]);
define('TIME_LIMITS_TO_TRY', [0,300,240,200,180,160,140,120,100,80,60,40,30]);
/** @var bool */
define('IS_CLI', PHP_SAPI === 'cli');
/** @var array */
define('ARGS', Helper::getArgs(0));
/** @var array */
define('SUPPLIED_ARGS', Helper::getArgs(1));
/** @var float */
define('MULTIPLIER', ARGS['multiplier']);

//Try to set the max_execution_time as high as possible
Helper::trySetTimeLimits();

if(ARGS['threads'] > 1)
{
    $threadHandler = new ThreadHandler();
    $threadsData = $threadHandler->runThreads();

    $benchmarkHandler = BenchmarkHandler::fromThredsData($threadsData);

    Output::write($benchmarkHandler);
}
else
{
    //Running the Benchmarks
    $benchmarkHandler = new BenchmarkHandler(ARGS['groups'], ARGS['benchmarks'], ARGS['thread_id'], ARGS['master_sid']);
    $benchmarkHandler->runBenchmarks();

    Output::write($benchmarkHandler);
}


function checkPhpVersion($majorRequired, $minorReq)
{
    if(PHP_MAJOR_VERSION < $majorRequired || (PHP_MINOR_VERSION < $minorReq && PHP_MAJOR_VERSION === $majorRequired))
    {
        if(PHP_SAPI !== 'cli')
        {
            @http_response_code(500);
        }
        echo 'This script requires PHP 7.0 or higher.';
        exit(1);
    }
}