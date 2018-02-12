<?php

// example usage

	// debugging
		ini_set('display_errors', 1);
		error_reporting(E_ALL);

	// include class autoloader
		require __DIR__ . '/vendor/autoload.php';
		use FormManager\FormManager;

	// instantiate
		new FormManager(array(
			'installDir' => __DIR__
		));
