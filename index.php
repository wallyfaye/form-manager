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
			'formSchema' => array(
				'name' => array(
					'type' => 'group',
					'group_name' => 'Name',
					'features' => array(
						'duplicatable' => true, 
					),
					'children' => array(
						'first_name' => array(
							'type' => 'input_text',
							'field_name' => 'First Name',
							'features' => array(
								'validation' => array(
									'required' => true
								)
							)
						),
						'last_name' => array(
							'type' => 'input_text',
							'field_name' => 'Last Name',
							'features' => array(
								'validation' => array(
									'required' => true
								)
							)
						)
					)
				),
				'gender' => array(
					'type' => 'input_select',
					'field_name' => 'Gender',
					'field_feed' => array(
						array(
							'item_value' => 'male', 
							'item_name' =>  'Male'
						),
						array(
							'item_value' => 'female', 
							'item_name' =>  'Female'
						)
					)
				),
				'subscribe' => array(
					'type' => 'input_single_checkbox',
					'field_name' => 'Do you want to subscribe?',
					'field_feed' => array(
						array(
							'item_value' => 'yes', 
							'item_name' =>  'Yes'
						)
					)
				),
				'email' => array(
					'type' => 'input_text',
					'field_name' => 'Email',
					'features' => array(
						'validation' => array(
							'required' => true,
							'rule' => 'email'
						)
					)
				),
				'resume' => array(
					'type' => 'input_file',
					'field_name' => 'Resume',
					'features' => array(
						'validation' => array(
							'required' => true
						)
					)
				),
				'bio' => array(
					'type' => 'input_textarea',
					'field_name' => 'Bio'
				)
			),
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
							print_r($fm->inputValue);
						}
					break;
					
					case 'o':
						echo 'o';
					break;
				}
			};
		}
