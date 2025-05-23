<?php
/**
 * Here your can define your own custom benchmarks.
 * Mare sure to return an array of Benchmark objects - see example below
 * @see \Benchmark
 */

return
[
	//Example:
	/*
	new Benchmark('myBenchmark', 'myGroup', 
		function ($count = 200000)
		{
			$count = $count * MULTIPLIER;
			for ($i = 0; $i < $count; $i++) 
			{
				rand(0,9999);
			}
		},
		function()
		{
			//Optional
			//A functions that runs before your actual benchmark
			//Use the $this->data array to exchange data with your benchark function
		},
		function()
		{
			//Optional
			//A functions that runs after your actual benchmark
			//Use the $this->data array to exchange data with your benchark function
		}
	)*/
];
