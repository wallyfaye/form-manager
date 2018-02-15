<?php

	// debugging
		ini_set('display_errors', 1);
		error_reporting(E_ALL);

	// include class autoloader
		require __DIR__ . '/vendor/autoload.php';
		use FormManager\FormManager;

	// instantiate
		$fm = new FormManager(array(
			'installDir' => __DIR__,
			'inputHash' => 'fldkjfalkfjalkfjalkfa',
			'inputValues' => array(
				array(
					'id' => 'fldkjfalkfjalkfjalkfa', 
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
