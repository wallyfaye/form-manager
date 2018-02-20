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
			'fieldGroups' => array(
				array(
					'fields' => array(
						array(
							'id' => 'intro',
							'type' => 'html',
							'field_data' => '<h1>Hello World</h1>'
						)
					)
				),
				array(
					'duplicatable' => true,
					'fields' => array(
						array(
							'id' => 'first_name',
							'field_text' => 'First Name',
							'type' => 'input_text',
							'required' => true
						),
						array(
							'id' => 'last_name',
							'field_text' => 'Last Name',
							'type' => 'input_text',
							'required' => true
						)
					)
				),
				array(
					'fields' => array(
						array(
							'id' => 'gender',
							'field_text' => 'Gender',
							'type' => 'input_select',
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
						)
					)
				),
				array(
					'fields' => array(
						array(
							'id' => 'subscribe',
							'field_text' => 'Do you want to subscribe?',
							'type' => 'input_single_checkbox',
							'field_feed' => array(
								array(
									'item_value' => 'yes', 
									'item_name' =>  'Yes'
								)
							)
						)
					)
				),
				array(
					'fields' => array(
						array(
							'id' => 'email',
							'field_text' => 'Email Address',
							'type' => 'input_text',
							'required' => true,
							'validation' => 'email'
						)
					)
				),
				array(
					'fields' => array(
						array(
							'id' => 'resume',
							'field_text' => 'Resume',
							'type' => 'input_file',
							'required' => true,
							'validation' => 'pdf'
						)
					)
				),
				array(
					'fields' => array(
						array(
							'id' => 'bio',
							'field_text' => 'Bio',
							'type' => 'input_textarea'
						)
					)
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
						if($fm->validateHash($v, 'input')){
							if($fm->validateSubmission($_POST, $_FILES)){
								echo 'did submit';
							} else {
								echo 'not submitted';
							}
						}
					break;
				}
			};
		}

	// some generic DOM render function
		function renderForm($formData, $v){
			$some_var = json_encode($formData);
			echo '<!DOCTYPE html>
			<html lang="en">
			<head>
				<meta charset="UTF-8">
				<title>Document</title>
			</head>
			<body>
				<form enctype="multipart/form-data" action="?m=o&v=' . $v . '" method="post">
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

