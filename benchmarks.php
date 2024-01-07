<?php
//Data used for the hash functions:
define('HASHING_DATA', 'egF7awirTJqPwXouhHOAZ3UmEnEbL9');
define('PWD_HASH', password_hash(HASHING_DATA, PASSWORD_DEFAULT));
//Data used for json encode/decode:
define('JSON_DATA', ['string' => 'text', 'int' => 42, 'float' => 42.0, 'bool' => true, 'null' => null, 'array' => [1,2,3,4,5,6,7,8]]);

//Make sure to return an array of type Benchmark
return
[
	#region fast
	new Benchmark('closure', 'fast', function ($count = 200000)
	{
		$count = $count * MULTIPLIER;

		$closure = function($a, $b){return $a + $b;};
		for ($i = 0; $i < $count; $i++) 
		{
			$closure(1,2);
		}
	}),
	new Benchmark('closure_ivoke', 'fast', function ($count = 200000)
	{
		$count = $count * MULTIPLIER;
		
		$closure = function($a, $b){return $a + $b;};
		for ($i = 0; $i < $count; $i++) 
		{
			$closure->__invoke(1,2);
		}
	}),
	#endregion
	#region general
	new Benchmark('math', 'general', function ($count = 200000)
	{
		$x = 0;
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++) {
			$x += $i + $i;
			$x += $i * $i;
			$x += $i ** $i;
			$x += $i / (($i + 1) * 2);
			$x += $i % (($i + 1) * 2);
			abs($i);
			acos($i);
			acosh($i);
			asin($i);
			asinh($i);
			atan2($i, $i);
			atan($i);
			atanh($i);
			ceil($i);
			cos($i);
			cosh($i);
			decbin($i);
			dechex($i);
			decoct($i);
			deg2rad($i);
			exp($i);
			expm1($i);
			floor($i);
			fmod($i, $i);
			hypot($i, $i);
			is_infinite($i);
			is_finite($i);
			is_nan($i);
			log10($i);
			log1p($i);
			log($i);
			pi();
			pow($i, $i);
			rad2deg($i);
			sin($i);
			sinh($i);
			sqrt($i);
			tan($i);
			tanh($i);
		}
	}),
	new Benchmark('loops', 'general', function ($count = 20000000) 
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; ++$i) {
			$i;
		}
		$i = 0;
		while ($i < $count) {
			++$i;
		}
	}),
	new Benchmark('ifelse', 'general', function ($count = 10000000)
	{
		$a = 0;
		$b = 0;
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++) {
			$k = $i % 4;
			if ($k === 0) {
				$i;
			} elseif ($k === 1) {
				$a = $i;
			} elseif ($k === 2) {
				$b = $i;
			} else {
				$i;
			}
		}
	}),
	new Benchmark('switch', 'general', function ($count = 10000000)
	{
		$a = 0;
		$b = 0;
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++) {
			switch ($i % 4) {
				case 0:
					$i;
					break;
				case 1:
					$a = $i;
					break;
				case 2:
					$b = $i;
					break;
				default:
					break;
			}
		}
	}),
	new Benchmark('string', 'general', function ($count = 50000)
	{
		$string = '<i>the</i> quick brown fox jumps over the lazy dog  ';
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++) {
			addslashes($string);
			bin2hex($string);
			chunk_split($string);
			convert_uudecode(convert_uuencode($string));
			count_chars($string);
			explode(' ', $string);
			htmlentities($string);
			md5($string);
			metaphone($string);
			ord($string);
			rtrim($string);
			sha1($string);
			soundex($string);
			str_getcsv($string);
			str_ireplace('fox', 'cat', $string);
			str_pad($string, 50);
			str_repeat($string, 10);
			str_replace('fox', 'cat', $string);
			str_rot13($string);
			str_shuffle($string);
			str_word_count($string);
			strip_tags($string);
			strpos($string, 'fox');
			strlen($string);
			strtolower($string);
			strtoupper($string);
			substr_count($string, 'the');
			trim($string);
			ucfirst($string);
			ucwords($string);
		}
	}),
	new Benchmark('array', 'general', function ($count = 50000)
	{
		$a = range(0, 100);
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++) {
			array_keys($a);
			array_values($a);
			array_flip($a);
			array_map(function ($e) {}, $a);
			array_walk($a, function ($e, $i) {});
			array_reverse($a);
			array_sum($a);
			array_merge($a, [101, 102, 103]);
			array_replace($a, [1, 2, 3]);
			array_chunk($a, 2);
		}
	}),
	new Benchmark('regex', 'general', function ($count = 1000000)
	{
		$count = $count * MULTIPLIER;

		for ($i = 0; $i < $count; $i++) {
			preg_match("#http[s]?://\w+[^\s\[\]\<]+#",
				'this is a link to https://google.com which is a really popular site');
			preg_replace("#(^|\s)(http[s]?://\w+[^\s\[\]\<]+)#i", '\1<a href="\2">\2</a>',
				'this is a link to https://google.com which is a really popular site');
		}
	}),
	new Benchmark('is_type', 'general', function ($count = 2500000)
	{
		$o = new stdClass();
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++) {
			is_array([1]);
			is_array('1');
			is_int(1);
			is_int('abc');
			is_string('foo');
			is_string(123);
			is_bool(true);
			is_bool(5);
			is_numeric('hi');
			is_numeric('123');
			is_float(1.3);
			is_float(0);
			is_object($o);
			is_object('hi');
		}
	}),
	new Benchmark('json_encode', 'general', function ($count = 100000)
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++) 
		{
			json_encode(JSON_DATA);
		}
	}),
	new Benchmark('json_decode', 'general', function ($count = 100000)
	{
		$count = $count * MULTIPLIER;
		$jsonData = json_encode(JSON_DATA);
		for ($i = 0; $i < $count; $i++) 
		{
			json_decode($jsonData, true);
			json_decode($jsonData, false);
		}
	}),
	#endregion

	#region hash
	new Benchmark('password_hash', 'hash', function ($count = 20)
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++){ password_hash(HASHING_DATA, PASSWORD_DEFAULT); }
	}),
	new Benchmark('password_verify', 'hash', function ($count = 20)
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++){ password_verify(HASHING_DATA, PWD_HASH); }
	}),
	new Benchmark('md5', 'hash', function ($count = 50000)
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++){ md5(HASHING_DATA); }
	}),
	new Benchmark('sha1', 'hash', function ($count = 50000)
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++){ sha1(HASHING_DATA); }
	}),
	new Benchmark('sha256', 'hash', function ($count = 50000)
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++){ hash('sha256', HASHING_DATA); } 
	}),
	new Benchmark('sha512', 'hash', function ($count = 50000)
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++){ hash('sha512', HASHING_DATA); } 
	}),
	new Benchmark('crc32', 'hash', function ($count = 50000)
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++){ hash('sha512', HASHING_DATA); } 
	}),
	new Benchmark('ripemd160', 'hash', function ($count = 50000)
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++){ hash('ripemd160', HASHING_DATA); } 
	}),
	new Benchmark('other_hashes', 'hash', function ($count = 50000)
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++) {
			hash('crc32b', HASHING_DATA);
			hash('adler32', HASHING_DATA);
			hash('fnv132', HASHING_DATA);
			hash('fnv164', HASHING_DATA);
			hash('joaat', HASHING_DATA);
			hash('haval128,5', HASHING_DATA);
			hash('haval160,5', HASHING_DATA);
			hash('haval192,5', HASHING_DATA);
			hash('haval224,5', HASHING_DATA);
			hash('haval256,5', HASHING_DATA);
		}
	}),
	#endregion

	#region io
	new Benchmark('file_read', 'io', function($count = 1000)
	{
		file_put_contents('test.txt', "test");
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++) {
			file_get_contents('test.txt');
		}
		unlink('test.txt');
	}),
	new Benchmark('file_write', 'io', function($count = 1000)
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++) {
			file_put_contents('test.txt', "test $i");
		}
		unlink('test.txt');
	}),
	new Benchmark('file_zip', 'io', function($count = 1000)
	{
		file_put_contents('test.txt', "test");
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++) {
			$zip = new ZipArchive();
			$zip->open('test.zip', ZipArchive::CREATE);
			$zip->addFile('test.txt');
			$zip->close();
		}
		unlink('test.txt');
		unlink('test.zip');
	}),
	new Benchmark('file_unzip', 'io', function($count = 1000)
	{
		file_put_contents('test.txt', "test");
		$zip = new ZipArchive();
		$zip->open('test.zip', ZipArchive::CREATE);
		$zip->addFile('test.txt');
		$zip->close();
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++) {
			$zip = new ZipArchive();
			$zip->open('test.zip');
			$zip->extractTo('test');
			$zip->close();
		}
		unlink('test.txt');
		unlink('test.zip');
		unlink('test/test.txt');
		rmdir('test');
	}),
	#endregion

	#region rand
	new Benchmark('rand', 'rand', function($multiplier = 1, $count = 1000000)
	{
		$count = $count * $multiplier;
		for ($i = 0; $i < $count; $i++) {
			rand(0, $i);
		}
	}),
	new Benchmark('mt_rand', 'rand', function($multiplier = 1, $count = 1000000)
	{
		$count = $count * $multiplier;
		for ($i = 0; $i < $count; $i++) {
			mt_rand(0, $i);
		}
	}),
	new Benchmark('random_int', 'rand', function($multiplier = 1, $count = 1000000)
	{
		if (!function_exists('random_int')) 
		{
			throw new Exception('Function "random_int" does not exist.');
		}

		$count = $count * $multiplier;
		for ($i = 0; $i < $count; $i++) {
			random_int(0, $i);
		}
	}),
	new Benchmark('random_bytes', 'rand', function($multiplier = 1, $count = 1000000)
	{
		if (!function_exists('random_bytes')) {
			throw new Exception('Function "random_bytes" does not exist.');
		}

		$count = $count * $multiplier;
		for ($i = 0; $i < $count; $i++) {
			random_bytes(32);
		}
	}),
	new Benchmark('openssl_random_pseudo_bytes', 'rand', function($multiplier = 1, $count = 1000000)
	{
		if (!function_exists('openssl_random_pseudo_bytes')) {
			throw new Exception('Function "openssl_random_pseudo_bytes" does not exist.');
		}

		$count = $count * $multiplier;
		for ($i = 0; $i < $count; $i++) {
			openssl_random_pseudo_bytes(32);
		}
	})
	#endregion
];
