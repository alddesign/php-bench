<?php
/**
 * Do not modify this file.
 * 
 * Contains the actual benchmarks of php-bench. 
 * If you want to add your own benchmarks, do it in `benchmarksCustom.php`
 */


/** @var string String used for string functions */
define('STR', ' <i>the</i> quick brown fox <b>jumps</b> over the lazy dog ');

/** @var string Data used for the hash functions */
define('HASH_DATA', 'egF7awirTJqPwXouhHOAZ3UmEnEbL9ocBQJRgBGqAOxCq1liyczO8sNhSXdm');

/** @var string Data used for the en/decryption functions */
define('CRYPTO_DATA', 'UxkBLZeuustvBB0ttWElsbkBgWf7Sey0M5lYdkql7W9pGJhU7VeEzGvS9gap');

/** @var string 256bit binary string key */
define('CRYPTO_KEY', hex2bin('0699af15ecf59480040b323bcf71f16b3281e7ced7e26a695a2ce8086ead5f5f'));

/** @var string 128bit binary string initalization vector */
define('CRYPTO_IV', hex2bin('0c99d061dee858f5ce56825444afd983'));

/** @var array Data used for json encode/decode */
define('JSON_DATA', ['string' => 'text', 'int' => 42, 'float' => 42.0, 'bool' => true, 'null' => null, 'array' => [1,2,3,4,5,6,7,8]]);

//Data unsed for file io and zip
define('IO_DATA', '8e471ff3d0544253722dbe5f64961d05ac45a4ae7f8460be6becb3252f9df9095f05ec5ef9f07c5a49607e66e8e4da2ac8bfa08639bd31d39582b947cae0c746a0269064b0fa1dd44e8f02e98f1812cd4e5bad542f48ef1384031b570efa070258e4af0938c625c258017db28fa471b972a4e2ce7606c84fd51dddcc16f13401d3a84483a3d6505cf55a4342b3ff84e0ee2052f992fdcc230e804bae82eec6968ccce3fdff38aa320bdd6e1da609c930a19ceabe02f5fab60dd0acefd51c088f2f2b7f65ca31982bc67d6240dddeabb7788ebcb768d29f0b84c6a31e877c6e6f72eb3ac51c9054d71c11137fc99fe3d8b2d9c7e1cc884eac72ceafb3dd536db5');


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
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++) {
			addslashes(STR);
			bin2hex(STR);
			chunk_split(STR);
			convert_uudecode(convert_uuencode(STR));
			count_chars(STR);
			htmlentities(STR);
			md5(STR);
			metaphone(STR);
			ord(STR);
			rtrim(STR);
			sha1(STR);
			soundex(STR);
			str_getcsv(STR,',','"','\\');
			str_ireplace('fox', 'cat', STR);
			str_pad(STR, 50);
			str_repeat(STR, 10);
			str_replace('fox', 'cat', STR);
			str_rot13(STR);
			str_shuffle(STR);
			str_word_count(STR);
			strip_tags(STR);
			strpos(STR, 'fox');
			strlen(STR);
			strtolower(STR);
			strtoupper(STR);
			substr_count(STR, 'the');
			trim(STR);
			ucfirst(STR);
			ucwords(STR);
		}
	}),
	new Benchmark('array_basics', 'general', function ($count = 200000)
	{
		//Rather than array functions like below, we test "basic" array operations like initialization, accessing, setting, adding and unsetting elements.
		$count = $count * MULTIPLIER;
		
		for ($i = 0; $i < $count; $i++) 
		{
			//initialize
			$a = ['7b070a9913d3','fc585047260b','406ab75aa402','a5dc961e2bea','2791fc357687','bb5be0c517f6','c0118485cc07','c4df712e1d35','2c3fc9264742','86228a4f0178','df2f2964dd6e','a0e5202237ed','0460076d38ed','15cca9f08f79','da1b9b076fcd','3bf3d6df2f02','92e6c3fa2465','aa3bca52339e','d62a9f762380','42c3ed3d2278','ff415c10bdbd','56c1b52863fe','a6fd85f0abd3','ca048e572d62','7d5381bcbe89','99b51da3f162','ff6ef9c96fcd','741eab675632','52d241bbff9a','02c460f5a1f9','7f31a4aff76b','57a617c378f6','495f9b95fbfb','d47a2b29d1bd','36d3460d807f','fc1a0da41576','9360e9a5c7b5','8fdff5a0d73e','7aa2be45a802','fbcecff3d4fa','112316735b5c','df459e2b75e4','ee547038e39d','5be18a6c5b8a','34585203f6e7','c21c2eb3c8f3','c5964e318888','81b575406268','30af8bbd8c6d','1c0b6331a780',];

			//add elements via index
			$a[64] = 'b256e2e430ee';
			$a[80] = '5d2ec65e6a67';
			$a[91] = '8b0e40cf402e';
			$a[53] = '2559d322fe02';
			$a[97] = 'c51ca6fe51d4';
			$a[69] = '5259ff3258a4';
			$a[62] = '038054751359';
			$a[50] = '45ac5c722c4a';
			$a[86] = 'f65f82d4827c';
			$a[66] = '6576527862ce';
			$a[92] = '37ed3600af35';
			$a[67] = '0d4f0a57722c';
			$a[82] = '6ddab22560a8';
			$a[95] = '66cbe17c3233';
			$a[56] = '8811678a5adf';
			$a[52] = '16ff94a68b18';
			$a[58] = '068d76ba5c75';
			$a[61] = 'b752eaac35aa';
			$a[96] = 'a2b814e41974';
			$a[68] = 'ff64007fee45';
			$a[65] = 'fb529c79cbbe';
			$a[54] = 'b1c4d4f3bb91';
			$a[89] = '754439464d7d';
			$a[79] = 'de0e41ddb743';
			$a[78] = '6dbd30ca152e';
			$a[81] = 'a4a00570dd5c';
			$a[63] = '8eb829a6739a';
			$a[90] = '9c6d839b207f';
			$a[75] = '8125e3a401d0';
			$a[87] = 'a478f47a1b41';
			$a[88] = '438c9c31892f';
			$a[73] = '8d8e425d929a';
			$a[71] = '0c514bd45cc6';
			$a[83] = 'c87fc8cc8817';
			$a[76] = '8320ed12e9a4';
			$a[85] = '7a923fd08522';
			$a[70] = '60e061456c6a';
			$a[72] = 'f8f4e30d5ce8';
			$a[57] = 'a9d23273bde4';
			$a[99] = '3c98359847a4';
			$a[77] = 'b00a98fecb7a';
			$a[93] = '8614fb4fefb7';
			$a[74] = '8c857786df94';
			$a[94] = '7a48f948c901';
			$a[98] = '8cf0ff12adb2';
			$a[60] = '69577e22911a';
			$a[51] = '646655e8915b';
			$a[59] = '03609e680775';
			$a[84] = '4b125bb05cdf';
			$a[55] = '7f9821f37d4e';

			//add elements blunt
			$a[] = '6212e05335f8';
			$a[] = '6257fd85d851';
			$a[] = 'ef8bd2cf482d';
			$a[] = '37c8818458d7';
			$a[] = '02128c89cb98';
			$a[] = '5789cfdbbb7d';
			$a[] = '09c43770e1e0';
			$a[] = '5b8cd2e972e8';
			$a[] = '8b394a25b42b';
			$a[] = '8c683d2befdd';
			$a[] = '2f7ef29d6bc4';
			$a[] = 'f69869695ef0';
			$a[] = 'd3445e5d43f2';
			$a[] = '5d72d2da91e4';
			$a[] = 'b9d36a904ae4';
			$a[] = '1d598da44478';
			$a[] = '094376037005';
			$a[] = '8f839f75ac3b';
			$a[] = '3f0c02ddeead';
			$a[] = '74bccd29cabf';
			$a[] = '6f85957df0e6';
			$a[] = '8ff5f3b29f75';
			$a[] = 'd3f36b8948f1';
			$a[] = '662d63607267';
			$a[] = '51082bb24fea';
			$a[] = 'e5e7336dee89';
			$a[] = '2361529a6992';
			$a[] = '28123d3737b0';
			$a[] = '5dfd3cfc0661';
			$a[] = '93eec177cf1e';
			$a[] = 'f3731307a3c4';
			$a[] = '250bc3ce3ce9';
			$a[] = 'd8c7065534df';
			$a[] = 'c5b7bc30a021';
			$a[] = 'f85aee4bfe3e';
			$a[] = '0fd5d91c4737';
			$a[] = '5135283ec4f9';
			$a[] = '678b0c272c24';
			$a[] = '78d20b0320d2';
			$a[] = 'd4d5474fcf81';
			$a[] = 'aacd6ff424d5';
			$a[] = '53671684e366';
			$a[] = 'f0d6f7139f32';
			$a[] = '6f9c6a3c39ab';
			$a[] = '323cdea6b642';
			$a[] = '5e0cfb7dbb56';
			$a[] = '415d2f58b974';
			$a[] = 'ef5bfbefa4de';
			$a[] = 'de84277701f9';
			$a[] = 'dbeeacb60b9b';

			//access random index and setting values
			$a[20] = '573095b738fd';
			$a[30] = '31c321e96123';
			$a[45] = '60cdf51613c7';
			$a[32] = '6f901c9e0832';
			$a[49] = '08371d228cfa';
			$a[26] = '542834862210';
			$a[3] = '9b47e28f5084';
			$a[22] = 'b380594fcf86';
			$a[44] = 'ed574d485a1d';
			$a[23] = 'd37f0e5d78f6';
			$a[33] = '318747c69e17';
			$a[29] = '47a64add1baa';
			$a[42] = '82cf8c96aefc';
			$a[17] = 'ca112b8ad6e9';
			$a[15] = '0791651250b3';
			$a[13] = 'f4689499f679';
			$a[21] = '6253af794dcc';
			$a[4] = '05530b85c4b9';
			$a[9] = '9e2edde5186d';
			$a[2] = '0497ff0e500d';
			$a[16] = 'a96024ff049b';
			$a[47] = 'f6229ee8de49';
			$a[35] = 'edbb3173b7f2';
			$a[25] = 'abe31e954649';
			$a[31] = 'c9e12af3cc42';
			$a[10] = '27bdf537b5d8';
			$a[14] = '88c0a2aa0dab';
			$a[0] = 'd2cf30098a7c';
			$a[19] = '12b13c9dbc75';
			$a[28] = '6dace669f305';
			$a[6] = '1515e7dc02a9';
			$a[5] = 'afadefe69946';
			$a[7] = '7913ca0c9bfd';
			$a[39] = '420b2358404b';
			$a[40] = '4ccedaf0e6df';
			$a[37] = '5f00a73cc56e';
			$a[12] = 'd8412d78229c';
			$a[48] = 'bc382ba13e5e';
			$a[43] = 'ae6ea18da6bb';
			$a[38] = '714f7352ed2b';
			$a[41] = 'f7f0bc52d833';
			$a[46] = 'a548bd857153';
			$a[27] = 'f39660e9313b';
			$a[24] = 'c6b4c2ea770a';
			$a[1] = 'fe31d9641c83';
			$a[18] = 'c71093416f52';
			$a[34] = '174fad79a2bd';
			$a[36] = '1f5ae7bb84f6';
			$a[8] = '97e112b135dc';
			$a[11] = '2ba2abe327b6';

			//isset on existing index
			isset($a[15]);
			isset($a[12]);
			isset($a[48]);
			isset($a[1]);
			isset($a[39]);
			isset($a[42]);
			isset($a[36]);
			isset($a[8]);
			isset($a[22]);
			isset($a[49]);
			isset($a[37]);
			isset($a[29]);
			isset($a[19]);
			isset($a[0]);
			isset($a[9]);
			isset($a[33]);
			isset($a[30]);
			isset($a[38]);
			isset($a[26]);
			isset($a[44]);
			isset($a[31]);
			isset($a[43]);
			isset($a[2]);
			isset($a[16]);
			isset($a[24]);
			isset($a[13]);
			isset($a[40]);
			isset($a[46]);
			isset($a[11]);
			isset($a[28]);
			isset($a[45]);
			isset($a[6]);
			isset($a[32]);
			isset($a[21]);
			isset($a[35]);
			isset($a[3]);
			isset($a[5]);
			isset($a[25]);
			isset($a[10]);
			isset($a[18]);
			isset($a[34]);
			isset($a[4]);
			isset($a[47]);
			isset($a[23]);
			isset($a[41]);
			isset($a[20]);
			isset($a[7]);
			isset($a[14]);
			isset($a[27]);
			isset($a[17]);

			//isset on non existing index
			isset($a[192]);
			isset($a[176]);
			isset($a[162]);
			isset($a[167]);
			isset($a[165]);
			isset($a[198]);
			isset($a[182]);
			isset($a[194]);
			isset($a[188]);
			isset($a[172]);
			isset($a[195]);
			isset($a[174]);
			isset($a[181]);
			isset($a[187]);
			isset($a[160]);
			isset($a[159]);
			isset($a[170]);
			isset($a[155]);
			isset($a[163]);
			isset($a[156]);
			isset($a[191]);
			isset($a[199]);
			isset($a[179]);
			isset($a[175]);
			isset($a[186]);
			isset($a[166]);
			isset($a[157]);
			isset($a[164]);
			isset($a[178]);
			isset($a[169]);
			isset($a[193]);
			isset($a[171]);
			isset($a[177]);
			isset($a[180]);
			isset($a[151]);
			isset($a[161]);
			isset($a[197]);
			isset($a[196]);
			isset($a[154]);
			isset($a[168]);
			isset($a[183]);
			isset($a[185]);
			isset($a[173]);
			isset($a[152]);
			isset($a[158]);
			isset($a[150]);
			isset($a[153]);
			isset($a[190]);
			isset($a[184]);
			isset($a[189]);

			//unset elements
			unset($a[20]);
			unset($a[40]);
			unset($a[44]);
			unset($a[45]);
			unset($a[39]);
			unset($a[34]);
			unset($a[0]);
			unset($a[36]);
			unset($a[46]);
			unset($a[37]);
			unset($a[14]);
			unset($a[29]);
			unset($a[8]);
			unset($a[18]);
			unset($a[24]);
			unset($a[35]);
			unset($a[28]);
			unset($a[31]);
			unset($a[41]);
			unset($a[27]);
			unset($a[9]);
			unset($a[33]);
			unset($a[3]);
			unset($a[49]);
			unset($a[13]);
			unset($a[15]);
			unset($a[25]);
			unset($a[21]);
			unset($a[43]);
			unset($a[23]);
			unset($a[6]);
			unset($a[22]);
			unset($a[26]);
			unset($a[48]);
			unset($a[32]);
			unset($a[7]);
			unset($a[47]);
			unset($a[11]);
			unset($a[19]);
			unset($a[12]);
			unset($a[10]);
			unset($a[5]);
			unset($a[16]);
			unset($a[30]);
			unset($a[2]);
			unset($a[1]);
			unset($a[42]);
			unset($a[38]);
			unset($a[17]);
			unset($a[4]);

			//unset the array
			unset($a);
		}
	}),
	new Benchmark('array_basics_assoc', 'general', function ($count = 200000)
	{
		//Rather than array functions like below, we test "basic" array operations like initialization, accessing, setting, adding and unsetting elements.
		$count = $count * MULTIPLIER;

		for ($i = 0; $i < $count; $i++) 
		{
			//init
			$a = ['5bbc579d4319'=>'b890fb410e1d','a16e2783d142'=>'803aae9d42f5','9e49cf1eef9d'=>'4cd75bb72ce9','4de7dfb4ab37'=>'f79117172e8c','4b3aeb08eb31'=>'45ea33320f79','1cc3a4105c11'=>'31bac5ff4a78','1c7f86c2d3ce'=>'1ec8b73cbcc0','ab7c0ef1647a'=>'9e76798463b2','4bc5c162f575'=>'0aae45492bd8','ce31326e0e42'=>'8e6068457f3d','87b238d82636'=>'abe68dd37519','969e1b923e3f'=>'768650859db6','ad522e846c4e'=>'ca94171c2c22','3bab005f058c'=>'69c1a758ded5','e77cde1ac305'=>'d885b5367abf','f9de75920eb1'=>'11aba58c02a6','e4a6e8515331'=>'e3d4d8d7196a','0cb4c7c61691'=>'b3e83da53e31','69e45040b6ac'=>'99330d566510','dfda04d0e5cd'=>'363f8b6500f8','40cab86ffbbf'=>'c9b10eb253ea','b64efec8c97e'=>'202a5af94d0c','1052324e7a2a'=>'c2b38d7e18ba','96f4e61bf3b8'=>'cd265928846a','c3dc6babcb3e'=>'2cc5ed49ba11','194cfa5333af'=>'386902423ccf','84fc64f23c37'=>'e39680683bf3','9c115457fd4d'=>'63209722fb66','28216473191c'=>'1c6b5b10f58e','c53a6e55b01c'=>'db7f09e5a1ba','9ab8a2f4b195'=>'6cff72d790d6','0946ea3bd2f2'=>'67d0cc8f8e4a','8bcb54ef3943'=>'2c29e13b018c','2bcbc5a3c314'=>'7c2843a79d6b','f893276d6b5e'=>'ef3d53367dca','cc2831ec27f6'=>'c6124faf8112','5b5f775349fc'=>'836e84276870','d2794d89f022'=>'77da089abbf9','3193796583ac'=>'a91bb3ca9cd9','85d21187688b'=>'6e04ce2f170f','a9dcdfbe03d9'=>'b47f887e1465','c9815ac81e1a'=>'138a2614fce5','dd5fc58923e4'=>'a3d824b6fd9a','d50fe6d65c97'=>'119a8cce80a0','96fa3fd162e5'=>'291017e882e4','ae157ca66efd'=>'b91bf51aa1cd','465cb490f023'=>'acf97f094417','b43f1bd8b51f'=>'776454507c8b','8a42a1aabaad'=>'6557ba8efd99','a7d3c882a48c'=>'3fb5d1e9932e',];
			
			//add elements via assoc index
			$a['cba19c78088a'] = '6e771cade60a';
			$a['caf5ebdc5b67'] = '93ca34b00a44';
			$a['92df376719b5'] = '4e8d3e8adf91';
			$a['f2685dde654b'] = '4949ccee1a36';
			$a['8d063aa4c80e'] = '53119e730c81';
			$a['a460fd183067'] = '043ef3d133cf';
			$a['9c06a5f69464'] = '465fd09dd534';
			$a['3bf26498afb2'] = 'fed1d1e05932';
			$a['548cc80002e0'] = '77a5f73e296a';
			$a['35ef124ee12f'] = '23c105ceb701';
			$a['be7439877afc'] = '3e6c4b2b64ac';
			$a['4d88f913a78a'] = '38680db42d60';
			$a['a32652b1b4dc'] = '130bfd933962';
			$a['2b99e405a426'] = '969ce4d3dc35';
			$a['d8f4db25675f'] = '71454f65af2c';
			$a['512f992bae51'] = '7d47cd8260a3';
			$a['7d58b435e197'] = '88001afada71';
			$a['699a9ee7538c'] = '7d29381f46c8';
			$a['9747593bbdbf'] = '7ce5ed727b60';
			$a['98684af89a7c'] = '0fccbcb9ed90';
			$a['822c33b8cc4d'] = '358c4970fc69';
			$a['14521b336aaa'] = 'c43b68b57749';
			$a['4274b10d162e'] = '0595e5b2a6b3';
			$a['30e4733fb55d'] = 'a57674d7942f';
			$a['1a37de3765fd'] = '5731fdf8b541';
			$a['952960be2b86'] = 'c68995b78bf9';
			$a['fe524553d721'] = '2229b2f8b072';
			$a['90b9ef4852f5'] = '5a9db7d2fd0f';
			$a['a644903b1c7f'] = '756ecf3b73cd';
			$a['3732edf8698a'] = 'a3f015ea7fd1';
			$a['4fe1eaa5285f'] = '16fd524483d3';
			$a['f09eb2202571'] = 'c829d8d95dee';
			$a['92f6d4b241c3'] = 'dc927f394755';
			$a['f40093a7b81e'] = '7b56392b5385';
			$a['3e13b577f068'] = '7a48640a7ff2';
			$a['42a07cac056c'] = 'e143910128be';
			$a['28bff809a4d8'] = '913c4213ac07';
			$a['a9ffcb17a18e'] = '32cd70897cf0';
			$a['ec3fa76e4f14'] = 'bf591e1685a2';
			$a['87701e9bf099'] = 'b7bbbcb4b425';
			$a['b16381ba7739'] = 'a62b65d3bc80';
			$a['ac9c25a5616a'] = 'b874342625c7';
			$a['001af20e2d4e'] = '6519074b7a11';
			$a['60b6174fde12'] = 'c4ab450c2c0a';
			$a['edd6f60b8a39'] = '2fe16e979e25';
			$a['d4dd64886321'] = 'a39fbdea20aa';
			$a['fc2d4eeae174'] = 'dfbe184ec0e9';
			$a['b33a96ba94be'] = '7bdd1ca8be60';
			$a['997d66080a98'] = 'e24ddbc479ea';
			$a['f6cbbe43b541'] = '4a80f85377c6';

			//add elements blunt
			$a[] = '602529103c62';
			$a[] = '9eb9b68870c2';
			$a[] = 'b03928942a29';
			$a[] = 'c1d87df8c071';
			$a[] = '17e9c4fdde16';
			$a[] = 'c785028de34a';
			$a[] = '977e87912802';
			$a[] = '1dfd46b2cfff';
			$a[] = '1b5d046d552b';
			$a[] = '22e7648eb529';
			$a[] = 'b059197d733c';
			$a[] = 'a4265479117c';
			$a[] = 'd4834f2576d7';
			$a[] = '801b5eb5c043';
			$a[] = '7146d1ca9f17';
			$a[] = 'c84911b90bc7';
			$a[] = '160a91530fd4';
			$a[] = 'd36493135f6f';
			$a[] = 'f6cba0dfe8f9';
			$a[] = '6c8857bdae03';
			$a[] = '47dc90fe8def';
			$a[] = '0121c072c4cf';
			$a[] = 'aade2e75dc89';
			$a[] = '86b853a9f91a';
			$a[] = '0d412a27810a';
			$a[] = '35bb9c624c0e';
			$a[] = '9bc95a5729fb';
			$a[] = '5dfc72ed8780';
			$a[] = '74ee2485eebf';
			$a[] = '6a73921e03e1';
			$a[] = 'd82df9a88c60';
			$a[] = 'b45257a66677';
			$a[] = '4d7c52deead6';
			$a[] = '7c736a9abc1b';
			$a[] = '9f3870689ca6';
			$a[] = 'c30891c6e151';
			$a[] = '9cfdb7ade757';
			$a[] = '6150dab02fb1';
			$a[] = '4a97d9a49136';
			$a[] = 'ca2d154af823';
			$a[] = 'c796e2cae792';
			$a[] = '4fe4eb48e2ce';
			$a[] = '19e1c571ee9d';
			$a[] = 'b6719ffd5902';
			$a[] = '329952243975';
			$a[] = 'a33e55ff5f93';
			$a[] = 'b7c96f16f42e';
			$a[] = 'f1dc88254042';
			$a[] = '0ac44cf9d1df';
			$a[] = '6543aa3a4e9b';

			//access random index and setting values
			$a['9c115457fd4d'] = '45ed0a565dc7';
			$a['d2794d89f022'] = 'eee81376f776';
			$a['9e49cf1eef9d'] = 'eaaa2ce4accd';
			$a['a9dcdfbe03d9'] = '71fd39d2b4da';
			$a['5b5f775349fc'] = 'b3f0f77aa90d';
			$a['b64efec8c97e'] = '828fd8f1f622';
			$a['96f4e61bf3b8'] = 'f6bc018916d0';
			$a['194cfa5333af'] = '938897a01dd5';
			$a['ce31326e0e42'] = '7050c9fe08e9';
			$a['dfda04d0e5cd'] = '6ba1c75dcef1';
			$a['2bcbc5a3c314'] = '9aceb169df25';
			$a['1c7f86c2d3ce'] = '98140ab8db55';
			$a['a7d3c882a48c'] = '8e2add3f4559';
			$a['40cab86ffbbf'] = 'bd9b9466736d';
			$a['c3dc6babcb3e'] = 'cf7eaf6dfa4e';
			$a['e4a6e8515331'] = '68c816cd0001';
			$a['0cb4c7c61691'] = '921d31b7199a';
			$a['b43f1bd8b51f'] = '1de664056c19';
			$a['96fa3fd162e5'] = '07d17e6d8285';
			$a['4b3aeb08eb31'] = '3a8e4a609447';
			$a['f9de75920eb1'] = '289de98e602f';
			$a['d50fe6d65c97'] = 'b76cbb96e01f';
			$a['cc2831ec27f6'] = 'ea029193eb84';
			$a['8a42a1aabaad'] = 'eb026757c43a';
			$a['ae157ca66efd'] = 'cf17591ab02c';
			$a['ad522e846c4e'] = '7324e2c35d38';
			$a['28216473191c'] = 'b8bc0f3eb56d';
			$a['3193796583ac'] = '84fa9c9c616d';
			$a['a16e2783d142'] = '4a4e66492b5d';
			$a['0946ea3bd2f2'] = '8b6503e57230';
			$a['69e45040b6ac'] = '105ff5c4c84e';
			$a['465cb490f023'] = '0a59657de1f4';
			$a['3bab005f058c'] = '37e35479d81e';
			$a['c9815ac81e1a'] = 'c3eed2d6da09';
			$a['5bbc579d4319'] = 'b627ac757de0';
			$a['969e1b923e3f'] = 'd79cbcaebbef';
			$a['f893276d6b5e'] = 'd42021e6195d';
			$a['84fc64f23c37'] = 'dd46e4816c2b';
			$a['c53a6e55b01c'] = '03389f386a25';
			$a['e77cde1ac305'] = '423d396aa406';
			$a['4de7dfb4ab37'] = 'f46e784a914d';
			$a['9ab8a2f4b195'] = 'c49c5d3dae20';
			$a['ab7c0ef1647a'] = 'bfac7db8c1db';
			$a['8bcb54ef3943'] = '40b868d561e4';
			$a['85d21187688b'] = 'd9c93be39364';
			$a['dd5fc58923e4'] = 'a738d2c3a62b';
			$a['1cc3a4105c11'] = '6682766fae80';
			$a['1052324e7a2a'] = '9ffd679ac184';
			$a['4bc5c162f575'] = 'ccde83fd0865';
			$a['87b238d82636'] = '37ab795a241a';

			//isset on existing index
			isset($a['1cc3a4105c11']);
			isset($a['40cab86ffbbf']);
			isset($a['b43f1bd8b51f']);
			isset($a['0cb4c7c61691']);
			isset($a['c53a6e55b01c']);
			isset($a['69e45040b6ac']);
			isset($a['1052324e7a2a']);
			isset($a['d50fe6d65c97']);
			isset($a['85d21187688b']);
			isset($a['5b5f775349fc']);
			isset($a['ce31326e0e42']);
			isset($a['96f4e61bf3b8']);
			isset($a['a16e2783d142']);
			isset($a['ab7c0ef1647a']);
			isset($a['dd5fc58923e4']);
			isset($a['a9dcdfbe03d9']);
			isset($a['dfda04d0e5cd']);
			isset($a['87b238d82636']);
			isset($a['96fa3fd162e5']);
			isset($a['9e49cf1eef9d']);
			isset($a['ad522e846c4e']);
			isset($a['28216473191c']);
			isset($a['ae157ca66efd']);
			isset($a['9c115457fd4d']);
			isset($a['4b3aeb08eb31']);
			isset($a['9ab8a2f4b195']);
			isset($a['c3dc6babcb3e']);
			isset($a['1c7f86c2d3ce']);
			isset($a['8a42a1aabaad']);
			isset($a['cc2831ec27f6']);
			isset($a['b64efec8c97e']);
			isset($a['f9de75920eb1']);
			isset($a['3193796583ac']);
			isset($a['465cb490f023']);
			isset($a['4de7dfb4ab37']);
			isset($a['194cfa5333af']);
			isset($a['8bcb54ef3943']);
			isset($a['5bbc579d4319']);
			isset($a['f893276d6b5e']);
			isset($a['a7d3c882a48c']);
			isset($a['c9815ac81e1a']);
			isset($a['2bcbc5a3c314']);
			isset($a['e77cde1ac305']);
			isset($a['3bab005f058c']);
			isset($a['969e1b923e3f']);
			isset($a['e4a6e8515331']);
			isset($a['0946ea3bd2f2']);
			isset($a['d2794d89f022']);
			isset($a['4bc5c162f575']);
			isset($a['84fc64f23c37']);

			//isset on non existing index
			isset($a['378ced5de2f9']);
			isset($a['86e4cfbf76ef']);
			isset($a['afc8492e00bd']);
			isset($a['c9b8953174a1']);
			isset($a['7dc6fd184d94']);
			isset($a['4cc0c8d97c91']);
			isset($a['6ae25eabe5c9']);
			isset($a['e72fd9623454']);
			isset($a['09bf42fd8901']);
			isset($a['4ce45151b6a3']);
			isset($a['b9f62b48dc0d']);
			isset($a['1d0378ca6556']);
			isset($a['5dcede760ba2']);
			isset($a['eb4b05c78a0f']);
			isset($a['d1947e8b45b8']);
			isset($a['cc9ffa0dfeda']);
			isset($a['bbb465678901']);
			isset($a['c20c80302de1']);
			isset($a['f1223bf9ee14']);
			isset($a['5932d22b55d6']);
			isset($a['68a9471e5bf7']);
			isset($a['27aa5150f101']);
			isset($a['574c3f941e0f']);
			isset($a['fe52fae39513']);
			isset($a['bd45d2871714']);
			isset($a['8d2e63299af7']);
			isset($a['9d4c7c4cfa3c']);
			isset($a['a64904ff1562']);
			isset($a['ebb43bdaa7d3']);
			isset($a['45d41f143dc9']);
			isset($a['d4e1209ba7c1']);
			isset($a['2f2a09677350']);
			isset($a['4a6098fa16be']);
			isset($a['4032294ebeae']);
			isset($a['0439878f84ce']);
			isset($a['a518c9e59903']);
			isset($a['53e915a5ca22']);
			isset($a['add9a2f9143e']);
			isset($a['7947e19816cd']);
			isset($a['407b1ea9b9db']);
			isset($a['a287d946b032']);
			isset($a['bc15ed42d574']);
			isset($a['71ec7204fcab']);
			isset($a['cb55191feb2f']);
			isset($a['eb6a8e75a3f4']);
			isset($a['c9b9141d6a43']);
			isset($a['6ad2c6e61fe7']);
			isset($a['7e98b64dd399']);
			isset($a['2a64a7b4183f']);
			isset($a['3ffe0cfca6c9']);

			//unset elements
			unset($a['96f4e61bf3b8']);
			unset($a['d50fe6d65c97']);
			unset($a['c53a6e55b01c']);
			unset($a['c3dc6babcb3e']);
			unset($a['a16e2783d142']);
			unset($a['5bbc579d4319']);
			unset($a['4bc5c162f575']);
			unset($a['ab7c0ef1647a']);
			unset($a['85d21187688b']);
			unset($a['84fc64f23c37']);
			unset($a['5b5f775349fc']);
			unset($a['4b3aeb08eb31']);
			unset($a['ce31326e0e42']);
			unset($a['a9dcdfbe03d9']);
			unset($a['465cb490f023']);
			unset($a['40cab86ffbbf']);
			unset($a['dfda04d0e5cd']);
			unset($a['ad522e846c4e']);
			unset($a['f9de75920eb1']);
			unset($a['cc2831ec27f6']);
			unset($a['69e45040b6ac']);
			unset($a['28216473191c']);
			unset($a['8bcb54ef3943']);
			unset($a['a7d3c882a48c']);
			unset($a['3193796583ac']);
			unset($a['c9815ac81e1a']);
			unset($a['f893276d6b5e']);
			unset($a['1cc3a4105c11']);
			unset($a['8a42a1aabaad']);
			unset($a['b43f1bd8b51f']);
			unset($a['96fa3fd162e5']);
			unset($a['0946ea3bd2f2']);
			unset($a['1c7f86c2d3ce']);
			unset($a['1052324e7a2a']);
			unset($a['0cb4c7c61691']);
			unset($a['e4a6e8515331']);
			unset($a['3bab005f058c']);
			unset($a['e77cde1ac305']);
			unset($a['dd5fc58923e4']);
			unset($a['4de7dfb4ab37']);
			unset($a['9c115457fd4d']);
			unset($a['9ab8a2f4b195']);
			unset($a['ae157ca66efd']);
			unset($a['9e49cf1eef9d']);
			unset($a['d2794d89f022']);
			unset($a['194cfa5333af']);
			unset($a['969e1b923e3f']);
			unset($a['2bcbc5a3c314']);
			unset($a['b64efec8c97e']);
			unset($a['87b238d82636']);

			//unset the array
			unset($a);

		}
	}),
	new Benchmark('array_functions', 'general', function ($count = 50000)
	{
		$arr = $this->data['arr'];
        $count = $count * MULTIPLIER;
        for ($i = 0; $i < $count; $i++) 
		{
            array_keys($arr);
            array_values($arr);
            array_flip($arr);
            array_map(function ($e) {}, $arr);
            array_walk($arr, function ($e, $i) {});
            array_reverse($arr);
            array_sum($arr);
            array_merge($arr, [101, 102, 103]);
            array_replace($arr, [1, 2, 3]);
            array_chunk($arr, 2);
        }
	},
	function()
	{
		$this->data['arr'] = range(0, 100);
	}),
	new Benchmark('implode_explode', 'general', function ($count = 50000)
	{
        $count = $count * MULTIPLIER;
        for ($i = 0; $i < $count; $i++) 
		{
			#implode(';', )
        }
	}),
	new Benchmark('classes', 'general', function ($count = 100000)
	{
		$count = $count * MULTIPLIER;

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
	},
	function()
	{
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
	}
	),
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
		}
	}),
	#endregion

	#region hash
	new Benchmark('password_hash', 'hash', function ($count = 20)
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++){ password_hash(HASH_DATA, PASSWORD_BCRYPT, ['cost' => 10]); }
	}),

	new Benchmark('password_verify', 'hash', function ($count = 20)
	{
		$count = $count * MULTIPLIER;
		$passwordHash = $this->data['password_hash'];
		for ($i = 0; $i < $count; $i++){ password_verify(HASH_DATA, $passwordHash); }
	},
	function()
	{
		$this->data['password_hash'] = password_hash(HASH_DATA, PASSWORD_BCRYPT, ['cost' => 10]);
	}
	),

	new Benchmark('md5', 'hash', function ($count = 50000)
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++){ md5(HASH_DATA); }
	}),

	new Benchmark('sha1', 'hash', function ($count = 50000)
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++){ sha1(HASH_DATA); }
	}),

	new Benchmark('sha256', 'hash', function ($count = 50000)
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++){ hash('sha256', HASH_DATA); } 
	}),

	new Benchmark('sha512', 'hash', function ($count = 50000)
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++){ hash('sha512', HASH_DATA); } 
	}),

	new Benchmark('crc32', 'hash', function ($count = 50000)
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++){ hash('sha512', HASH_DATA); } 
	}),

	new Benchmark('ripemd160', 'hash', function ($count = 50000)
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++){ hash('ripemd160', HASH_DATA); } 
	}),

	new Benchmark('other_hashes', 'hash', function ($count = 50000)
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++) {
			hash('crc32b', HASH_DATA);
			hash('adler32', HASH_DATA);
			hash('fnv132', HASH_DATA);
			hash('fnv164', HASH_DATA);
			hash('joaat', HASH_DATA);
			hash('haval128,5', HASH_DATA);
			hash('haval160,5', HASH_DATA);
			hash('haval192,5', HASH_DATA);
			hash('haval224,5', HASH_DATA);
			hash('haval256,5', HASH_DATA);
		}
	}),
	#endregion

	#region crypt
	new Benchmark('aes_256_cbc_encrypt', 'crypt', function ($count = 20000)
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++)
		{ 
			openssl_encrypt(CRYPTO_DATA, 'aes-256-cbc', CRYPTO_KEY, OPENSSL_RAW_DATA, CRYPTO_IV); 
		}
	}),

	new Benchmark('aes_256_cbc_decrypt', 'crypt', function ($count = 20000)
	{
		$count = $count * MULTIPLIER;
		$ct = $this->data['ct'];
		for ($i = 0; $i < $count; $i++)
		{ 
			openssl_decrypt($ct, 'aes-256-cbc', CRYPTO_KEY, OPENSSL_RAW_DATA, CRYPTO_IV); 
		}
	},
	function()
	{
		$this->data['ct'] = openssl_encrypt(CRYPTO_DATA, 'aes-256-cbc', CRYPTO_KEY, OPENSSL_RAW_DATA, CRYPTO_IV);
	}),

	new Benchmark('aes_256_gcm_encrypt', 'crypt', function ($count = 20000)
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++)
		{ 
			openssl_encrypt(CRYPTO_DATA, 'aes-256-gcm', CRYPTO_KEY, OPENSSL_RAW_DATA, CRYPTO_IV, $tag); 
		}
	}),

	new Benchmark('aes_256_gcm_decrypt', 'crypt', function ($count = 20000)
	{
		$count = $count * MULTIPLIER;
		$ct = $this->data['ct'];
		$tag = $this->data['tag'];
		for ($i = 0; $i < $count; $i++)
		{ 
			openssl_decrypt($ct, 'aes-256-gcm', CRYPTO_KEY, OPENSSL_RAW_DATA, CRYPTO_IV, $tag); 
		}
	},
	function()
	{
		$this->data['ct'] = openssl_encrypt(CRYPTO_DATA, 'aes-256-gcm', CRYPTO_KEY, OPENSSL_RAW_DATA, CRYPTO_IV, $tag);
		$this->data['tag'] = $tag;
	}),

	new Benchmark('aes_256_ecb_encrypt', 'crypt', function ($count = 20000)
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++)
		{ 
			openssl_encrypt(CRYPTO_DATA, 'aes-256-ecb', CRYPTO_KEY, OPENSSL_RAW_DATA); 
		}
	}),

	new Benchmark('aes_256_ecb_decrypt', 'crypt', function ($count = 20000)
	{
		$count = $count * MULTIPLIER;
		$ct = $this->data['ct'];
		for ($i = 0; $i < $count; $i++)
		{ 
			openssl_decrypt($ct, 'aes-256-ecb', CRYPTO_KEY, OPENSSL_RAW_DATA); 
		}
	},
	function()
	{
		$this->data['ct'] = openssl_encrypt(CRYPTO_DATA, 'aes-256-ecb', CRYPTO_KEY, OPENSSL_RAW_DATA);
	}),
	#endregion

	#region file
	new Benchmark('read', 'file', function($count = 2000)
	{
		$count = $count * MULTIPLIER;

		$temp = $this->data['temp'];
		for ($i = 0; $i < $count; $i++)
		{
			file_get_contents($temp);
		}
	},
	function()
	{
		$this->data['temp'] = Helper::makeTempFile();
		file_put_contents($this->data['temp'], IO_DATA);
	},
	function()
	{
		unlink($this->data['temp']);
	}
	),

	new Benchmark('write', 'file', function($count = 2000)
	{
		$count = $count * MULTIPLIER;

		$temp = $this->data['temp'];
		for ($i = 0; $i < $count; $i++)
		{
			file_put_contents($temp, IO_DATA);
		}
	},
	function()
	{
		$this->data['temp'] = Helper::makeTempFile();
	},
	function()
	{
		unlink($this->data['temp']);
	}
	),

	new Benchmark('zip', 'file', function($count = 2000)
	{
		$count = $count * MULTIPLIER;

		$temp = $this->data['temp'];
		$zip = $this->data['zip'];
		for ($i = 0; $i < $count; $i++) 
		{
			$zip->open($temp, ZipArchive::OVERWRITE);
			$zip->addFromString('file.txt', IO_DATA);
			$zip->close();
		}
	},
	function()
	{
		$this->data['temp'] = Helper::makeTempFile();
		$this->data['zip'] = new ZipArchive();
	},
	function()
	{
		unlink($this->data['temp']);
	}),

	new Benchmark('unzip', 'file', function($count = 2000)
	{
		$count = $count * MULTIPLIER;

		$tempZip = $this->data['tempZip'] ;
		$outDir = $this->data['outDir'];
		$zip = $this->data['zip'];
		for ($i = 0; $i < $count; $i++) 
		{
			$zip->open($tempZip);
			$zip->extractTo($outDir); //overwrites existing file
			$zip->close();
		}
	},
	function()
	{
		$this->data['zip'] = new ZipArchive();
		$this->data['tempZip'] = Helper::makeTempFile();
		$this->data['outDir'] = dirname($this->data['tempZip']);
		$this->data['outFile'] = bin2hex(random_bytes(8)); //We need a random filename, so threads dont interact with each other when extracting

		//Initially create one zip archive to read from
		$zip = new ZipArchive();
		$zip->open($this->data['tempZip'], ZipArchive::OVERWRITE);
		$zip->addFromString($this->data['outFile'], IO_DATA);
		$zip->close();
	},
	function()
	{
		unlink($this->data['tempZip']);
		unlink($this->data['outDir'] . '/' . $this->data['outFile']);
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
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++) {
			random_int(0, $i);
		}
	},
	function ()
	{
		if (!function_exists('random_int')) 
		{
			throw new Exception('Function "random_int" does not exist.');
		}
	}
	),

	new Benchmark('random_bytes', 'rand', function($count = 1000000)
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++) {
			random_bytes(32);
		}
	},
	function()
	{
		if (!function_exists('random_bytes')) 
		{
			throw new Exception('Function "random_bytes" does not exist.');
		}
	}
	),

	new Benchmark('openssl_random_pseudo_bytes', 'rand', function($count = 1000000)
	{
		$count = $count * MULTIPLIER;
		for ($i = 0; $i < $count; $i++) {
			openssl_random_pseudo_bytes(32);
		}
	},
	function()
	{
		if (!function_exists('openssl_random_pseudo_bytes')) 
		{
			throw new Exception('Function "openssl_random_pseudo_bytes" does not exist.');
		}
	})
	#endregion
];
