<?php

	// debugging
		ini_set('display_errors', 1);
		error_reporting(E_ALL);

	// include class autoloader
		require __DIR__ . '/vendor/autoload.php';
		use FormManager\FormManager;

	// instantiate
		$fm = new FormManager(array(
			'installDir' => __DIR__
		));

		if($fm->install() == 'installed'){
			echo 'run app';
			// show input form
			// show form submissions
		}
