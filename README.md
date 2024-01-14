# php-bench
Simple script to benchmark PHP performance of your system.  
Fork of [sergix44/php-benchmark-script](https://github.com/sergix44/php-benchmark-script)

## Overview
[Installation](#installation)  
[Usage](#usage)  
[Arguments](#arguments)  
[Adding your own benchmarks](#adding-your-own-benchmarks)  
[Remarks](#remarks)  
[Results](#results)

## Installation
Download and extract this repo to your webroot, or to a location where your can call it via PHP-CLI.

## Usage 
**Via Browser:** Browse the url that points to the extracted folder.  
For example: http://localhost/php-bench-master/

**Via CLI:** Run the **index.html** with **php**.  
For example: `php ./php-bench-master/index.php`

## Arguments
There are several arguments to change the behavior of the benchmarks.

Via Browser you can supply these via the query string: http://localhost/php-bench/?multiplier=2.5&benchmarks=zip,unzip

Via CLI as argument: `php index.php --multiplier=2.5 --benchmarks=zip,unzip`

#### --multiplier (int|float)  
Reduce or increase the amount of work. This applies to all benchmarks. With a multiplier of 2 the tests will take double the time, with 0.5 half the time.
Default = 1.

#### --benchmarks (string)  
A comma separated string. Run only specific benchmarks(s). For example: `--benchmarks=zip,unzip`. See `benchmarks.php` for details.  
Default: *empty (run all)*.

#### --groups (string)  
A comma separated string. Run only specific group(s) of benchmarks. For example: `--groups=file,hash`. See `benchmarks.php` for details.  
Default: *empty (run all)*.

#### --json (bool)
Get the results as a json string.  
Default = false.

#### --threads (int)  
*(experimental)* Number of threads to run the test.  
Note that the work is not being split across the threads, so each thread runs the whole test in its full length. The `--multiplier`, as well as all other arguments apply to each thread!  
The results get calculated as an average across all threads divided by the number of threads.  
*Sessions need to be enabled for this.*  
Default = 0.

#### --thread_timeout (int)
*(experimental)* Only needed, when using `--threads`. The main script checks the status of the worker threads regularly. If a thread doesnt report back after this time limit (sec.) it is considered dead. A thread reports back after each single benchmark. This will prevent the script from running forever, waiting for a thread to finish. Note that this value gets multiplied by the `--multiplier`. So `--multiplier=3 --thread_timeout=30` means the real timeout is 90 sec. If you have a very slow system, some benchmark may take longer, so you might want to increase that value.  
Default = 30.

## Adding your own benchmarks
Writing your own benchmarks is pretty straight forward.  
See `benchmarks.php`. Just add your tests to the array that gets returned in that file. Each benchmark is an array element of type `Benchmark`.

Example for a benchmark:  
```php
//...

//tl;dr
new Benchmark('name', 'group', function($count = 10000){/*benchmark code here...*/}),

//A more detailed example
new Benchmark
(
    //The unique benchmark name
    'benchmark_name',

    //The name of the group the benchmark belongs to.
    //Each group has its own section in the output
    'group_name',

    //The function which contains the actual benchmark code
    function($count = 2000)
    {
        //Make sure not to forget the --multiplier argument
        $count = $count * MULTIPLIER;

        //Get the data from the preFn
        $tempFile = $this->data['tempFile']
        
        //Each benchmark's core should be some kind of loop 
        //which is directly affected by the --multiplier argument
        for($i = 0; $i < $count; $i++)
        {
            $content = file_get_contents($tempFile);
        }
    },

    //(optional) A function which gets executed before the benchmark.
    //You can do some prepwork here.
    //Its execution time will not be added to the result
    //use the $this->data array to exchange data with the other functions
    function()
    {
        $this->data['tempFile'] = tempnam();
    },

    //(optional) A function which gets executed after the benchmark has finished.
    //Its execution time will not be added to the result
    //use the $this->data array to exchange data with the other functions
    function()
    {
        unlink($this->data['tempFile']);
    }
),
//...
```

## Remarks
Make sure to disable PHP extension like **xdebug** when testing. This extension in particular has a huge impact on the performance.

The script will try to increase the PHP `max_execution_time` as high as possible to ensure the benchmarks have enough time to run. The output will show a warning if this is not possible. Depending on your system the script can take from a few seconds to a few minutes.

On windows i sometimes get the following PHP warning:  
`Warning: ZipArchive::close(): Renaming temporary file failed: Permission denied`  
This is windows defender trying to do some stuff.


## Results
Here are some of my test results for comparison. Running v1.0.0-beta.2.

|PHP|Interface|OS|System|Threads|Total time|
|-|-|-|-|-|-|
|8.1.10|Browser|Win10|AMD 5700G, 32GB DDR4@3800mhz, PCIe4 NVMe|1|7.564s|
|8.1.10|Browser|Win10|AMD 5700G, 32GB DDR4@3800mhz, PCIe4 NVMe|4|2.235s|
|8.1.10|Browser|Win10|AMD 5700G, 32GB DDR4@3800mhz, PCIe4 NVMe|8|1.497s|
|8.1.10|Browser|Win10|AMD 5700G, 32GB DDR4@3800mhz, PCIe4 NVMe|16|1.294s|
|8.3.1|Browser|VM Ubuntu 22.04 Desktop|AMD 5700G, 32GB DDR4@3800mhz, PCIe4 NVMe|1|9.290s|
|7.3.31|Cli|Debian 10|RaspberryPi 2|1|178.268s|
|7.3.31|Cli|Debian 10|RaspberryPi 2|2|91.261s|
|8.2.7|Cli|Debian 12|RaspberryPi 3B|1|97.265s|
|8.2.7|Cli|Debian 12|RaspberryPi 3B|2|60.460s|
|8.2.7|Cli|Debian 12|RaspberryPi 3B|4|44.516s|
|8.2.9|Browser|Linux|all-inkl.com hosting|1|8.867s|
|8.2.9|Browser|Linux|all-inkl.com hosting|4|2.195s|
|8.2.9|Browser|Linux|all-inkl.com hosting|8|1.096s|
|8.2.9|Browser|Linux|all-inkl.com hosting|16|0.551s|


