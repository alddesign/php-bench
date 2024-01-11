# php-bench
Simple script to benchmark PHP performance of your system.  

Fork of [sergix44/php-benchmark-script](https://github.com/sergix44/php-benchmark-script)

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
Reduce or increase the amount of work for all benchmarks. With a multiplier of 2 the tests will take double the time, with 0.5 half the time. Default: 1.

#### --benchmarks (string)  
A comma separated string. Run only specific benchmarks(s). For example: `--benchmarks=zip,unzip`. See `benchmarks.php` for details. Default: all.

#### --groups (string)  
A comma separated string. Run only specific group(s) of benchmarks. For example: `--groups=file,hash`. See `benchmarks.php` for details. Default: all.

#### --json (true|false)
Get the results as json. Default: false.

#### --threads (int)  
*(experimental - browser only)* Number of threads to run the test.  
Note that the work is not being split across the threads, so each thread runs the whole test in its full length. The `--multiplier`, as well as all other arguments apply to all threads!  
The results get calculated as an average across all threads divided by the number of threads.  
Sessions need to be enabled for this.