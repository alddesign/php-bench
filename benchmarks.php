<?php
//Data used for the hash functions:
define('HASHING_DATA', 'egF7awirTJqPwXouhHOAZ3UmEnEbL9');
define('PWD_HASH', password_hash(HASHING_DATA, PASSWORD_DEFAULT));
//Data used for json encode/decode:
define('JSON_DATA', ['string' => 'text', 'int' => 42, 'float' => 42.0, 'bool' => true, 'null' => null, 'array' => [1,2,3,4,5,6,7,8]]);
//Data unsed for file io and zip
define('IO_DATA', '8e471ff3d0544253722dbe5f64961d05ac45a4ae7f8460be6becb3252f9df9095f05ec5ef9f07c5a49607e66e8e4da2ac8bfa08639bd31d39582b947cae0c746a0269064b0fa1dd44e8f02e98f1812cd4e5bad542f48ef1384031b570efa070258e4af0938c625c258017db28fa471b972a4e2ce7606c84fd51dddcc16f13401d3a84483a3d6505cf55a4342b3ff84e0ee2052f992fdcc230e804bae82eec6968ccce3fdff38aa320bdd6e1da609c930a19ceabe02f5fab60dd0acefd51c088f2f2b7f65ca31982bc67d6240dddeabb7788ebcb768d29f0b84c6a31e877c6e6f72eb3ac51c9054d71c11137fc99fe3d8b2d9c7e1cc884eac72ceafb3dd536db5');

//Make sure to return an array of type Benchmark
return
[
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
	new Benchmark('if_else', 'general', function ($count = 10000000)
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
	new Benchmark('strings', 'general', function ($count = 50000)
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
	new Benchmark('n1', 'general', function ($count = 50000)
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++) 
		{
			//throw new Exception('Unsupported feature! And some additional text to make it a bit longer!');
		}
	}),
	new Benchmark('n2', 'general', function ($count = 50000)
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++) 
		{

		}
	}),
	new Benchmark('array_basics', 'general', function ($count = 200000)
	{
		//Rather than array functions like below, we test "basic" array operations like initialization, accessing, setting, adding and unsetting elements.
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++) 
		{
			//Initalizing a larger array
			$a = ['John', 'Jane', 'Mary', 'James', 'Emily', 'Michael', 'Sarah', 'Jessica', 'Jacob', 'Mohammed', 'Emma', 'Joshua', 'Amanda', 'Andrew', 'Daniel', 'Melissa', 'Joseph', 'Deborah', 'Patricia', 'Richard', 'Linda', 'Barbara', 'Robert', 'Susan', 'Dorothy', 'William', 'Nancy', 'Paul', 'Jennifer', 'Liam', 'Olivia', 'Noah', 'Riley', 'Jackson', 'Sophia', 'Aiden', 'Samantha', 'Lucas', 'Peyton', 'Mason', 'Madison', 'Logan', 'Nicole', 'Alexander', 'Heather', 'Ethan', 'Stephanie', 'David', 'Rebecca', 'Matthew'];
			$c = count($a);
			for($x = 0; $x < $c; $x++)
			{
				$a[$x] = $a[$c-$x-1]; //Setting and accessings elements
				isset($a[$x]); //isset on existing index
				isset($a['none']); //isset on nonesisting index		
				$a[$c] = 'Another'; //Add by index
				$a[] = 'New One'; //Add without index
				unset($a[$c], $a[$c+1]); //Unset the newly create elements
			}
		}
	}),
	new Benchmark('array_functions', 'general', function ($count = 50000)
	{
		$a = range(0, 100);
        $count = $count * MULTIPLIER;
        for ($i = 0; $i < $count; $i++) 
		{
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
	new Benchmark('classes', 'general', function ($count = 100000)
	{
		$count = $count * MULTIPLIER;

		class PhpBenchTestClass
		{
			public $a = 0;
			public static $b = 0;
			public function __destruct()
			{
				$this->a = null;
			}
			public function set($a)
			{
				$this->a = $a;
			}
			public function get()
			{
				return $this->a;
			}
			public static function staticSet($b)
			{
				self::$b = $b;
			}
			public static function staticGet()
			{
				return self::$b;
			}
		}

		for ($i = 0; $i < $count; $i++) {
			$o = new PhpBenchTestClass(1);
			$o->a = 2;
			$a = $o->a;
			$o->set(3);
			$o->get();
			PhpBenchTestClass::$b = 4;
			$b = PhpBenchTestClass::$b;
			PhpBenchTestClass::staticSet(5);
			PhpBenchTestClass::staticGet();
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
	new Benchmark('closures', 'general', function ($count = 200000)
	{
		$count = $count * MULTIPLIER;

		$closure = function($a, $b){return $a + $b;};
		for ($i = 0; $i < $count; $i++) 
		{
			$closure(1,2);
		}
	}),
	new Benchmark('closures_invoke', 'general', function ($count = 200000)
	{
		$count = $count * MULTIPLIER;
		
		$closure = function($a, $b){return $a + $b;};
		for ($i = 0; $i < $count; $i++) 
		{
			$closure->__invoke(1,2);
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

	#region file
	new Benchmark('read', 'file', function($count = 2000)
	{
		$temp = Helper::makeTempFile();
		file_put_contents($temp, IO_DATA);

		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++)
		{
			file_get_contents($temp);
		}
		unlink($temp);
	}),
	new Benchmark('write', 'file', function($count = 2000)
	{
		$temp = Helper::makeTempFile();

		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++)
		{
			file_put_contents($temp, IO_DATA);
		}
		unlink($temp);
	}),
	new Benchmark('zip', 'file', function($count = 2000)
	{
		$temp = Helper::makeTempFile();
		$zip = new ZipArchive();

		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++) 
		{
			$zip->open($temp, ZipArchive::OVERWRITE);
			$zip->addFromString('file.txt', IO_DATA);
			$zip->close();
		}
		unlink($temp);
	}),
	new Benchmark('unzip', 'file', function($count = 2000)
	{
		$tempZip = Helper::makeTempFile();
		$outDir = dirname($tempZip);
		$outFile = bin2hex(random_bytes(4)); //We need a random filename, so threads dont interact with each other when extracting

		//Initially create one zip archive to read from
		$zip = new ZipArchive();
		$zip->open($tempZip, ZipArchive::OVERWRITE);
		$zip->addFromString($outFile, IO_DATA);
		$zip->close();

		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++) 
		{
			$zip->open($tempZip);
			$zip->extractTo($outDir); //overwrites existing file
			$zip->close();
		}
		unlink($tempZip);
		unlink($outDir. '/' . $outFile);
	}),
	#endregion

	#region rand
	new Benchmark('rand', 'rand', function($count = 1000000)
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++) {
			rand(0, $i);
		}
	}),
	new Benchmark('mt_rand', 'rand', function($count = 1000000)
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++) {
			mt_rand(0, $i);
		}
	}),
	new Benchmark('random_int', 'rand', function($count = 1000000)
	{
		if (!function_exists('random_int')) 
		{
			throw new Exception('Function "random_int" does not exist.');
		}

		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++) {
			random_int(0, $i);
		}
	}),
	new Benchmark('random_bytes', 'rand', function($count = 1000000)
	{
		if (!function_exists('random_bytes')) {
			throw new Exception('Function "random_bytes" does not exist.');
		}

		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++) {
			random_bytes(32);
		}
	}),
	new Benchmark('openssl_random_pseudo_bytes', 'rand', function($count = 1000000)
	{
		if (!function_exists('openssl_random_pseudo_bytes')) {
			throw new Exception('Function "openssl_random_pseudo_bytes" does not exist.');
		}

		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++) {
			openssl_random_pseudo_bytes(32);
		}
	})
	#endregion
];
