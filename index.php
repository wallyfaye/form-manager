<?php

	// debugging
		ini_set('display_errors', 1);
		error_reporting(E_ALL);

	// include class autoloader
		require __DIR__ . '/vendor/autoload.php';
		use FormManager\FormManager;

	// instantiate
	// for ?m=i&v=1234
		$fm = new FormManager(array(
			'installDir' => __DIR__,
			'inputSalt' => '1234567890123456',
			'inputValues' => array(
				'NovstOCKb5aHffYvehwMkAjyZQ5TZYW631AMhVAsgev4ysN0HIHhD2dX2k0MidsuqYugyHzKOeRGebmrVpp' => array(
					'first_name' => 'John',
					'last_name' => 'Doe'
				)
			)
		));

		if($fm->install() == 'installed'){
			if (!($m = filter_input(INPUT_GET, 'm', FILTER_SANITIZE_STRING))) {
				$m = 'n'; 
			}
			if (!($v = filter_input(INPUT_GET, 'v', FILTER_SANITIZE_STRING))) {
				$v = 'n'; 
			}
			if($fm->validateApplicationMode($m)){
				switch ($m) {
					case 'i':
						if($fm->validateHash($v, 'input')){
							echo 'hash valid';
						} else {
							echo 'hash invalid';
						}
					break;
					
					case 'o':
						echo 'o';
					break;
				}
				// echo $m;
				// $paramsValidator = new Params();
				// if($paramsValidator->hash($value)){
				// 	echo 'ok';
				// } else {
				// 	echo 'no';
				// }
			};
		}
