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
				'intro' => array(
					'type' => 'html',
					'field_name' => 'Intro',
					'field_data' => '<h1>Hello World</h1>'
				),
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
							'required' => false,
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
							renderForm($fm->getFormData(), $v);
						}
					break;
					
					case 'o':
						$fm->processSubmission($_POST);
					break;
				}
			};
		}

	// some generic DOM render function
		function renderForm($formData, $v){
			// print_r($formData);
			$some_var = json_encode($formData);
			echo '<!DOCTYPE html>
			<html lang="en">
			<head>
				<meta charset="UTF-8">
				<title>Document</title>
			</head>
			<body>
				<form action="?m=o&v=' . $v . '" method="post">
					<label for="first_name">first_name</label><br />
					<input type="text" name="first_name" /><br />
					<br />
					<label for="last_name">last_name</label><br />
					<input type="text" name="last_name" /><br />
					<br />
					<label for="unlisted_first_name">unlisted_first_name</label><br />
					<input type="text" name="unlisted_first_name" /><br />
					<br />
					<label for="email">email</label><br />
					<input type="text" name="email" /><br />
					<br />
					<label for="resume">resume</label><br />
					<input type="file" name="resume" /><br />
					<br />
					<label for="unlisted_resume">unlisted_resume</label><br />
					<input type="file" name="unlisted_resume" /><br />
					<br />
					<input type="submit"></input>
				</form>
				<script>
					var data = ' . $some_var . ';
					console.log(data);
				</script>
			</body>
			</html>';
		}

