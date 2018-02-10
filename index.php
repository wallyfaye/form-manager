<?php

	// debugging
		ini_set('display_errors', 1);
		error_reporting(E_ALL);

	// include class autoloader
		require __DIR__ . '/vendor/autoload.php';

	// instantiate
		$model_json = new \FormManager\Inputter\Prepopulate(array(
			'salt' => 'some_random_string',
		));

		print_r($model_json->salt);