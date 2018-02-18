<?php

	namespace FormManager\Validator;

	class Params{

		/**
		* Validate installation directory
		*
		* @return boolean
		*/

		public static function directory($dir = ''){

			$dirValid = true;

			// if the directory is not set, fail
			if($dir == ''){
				$dirValid = false;
			}

			// if the directory is not a string, fail
			if(gettype($dir) != 'string'){
				$dirValid = false;
			}

			// if the directory ends with a trailing slash, fail
			if(substr($dir, -1) == '/'){
				$dirValid = false;
			}

			// if the directory does not exist and the parent is not writable, fail
			if(file_exists($dir) === false && is_writable(dirname($dir)) === false){
				$dirValid = false;
			}

			// if the directory does exist and it is not writable, fail
			if(file_exists($dir) === true && is_writable($dir) === false){
				$dirValid = false;
			}

			return $dirValid;

		}

		public static function values($values = ''){

			$valuesValid = true;

			// if the values is not set, fail
			if($values == ''){
				$valuesValid = false;
			}

			// if the values is not an array, fail
			if(gettype($values) != 'array'){
				$valuesValid = false;
			}

			if($valuesValid){
				if(count($values) == 0){
					$valuesValid = false;
				} else {
					foreach ($values as $key_values => $value_values) {

						// each value should be an array
						if(gettype($value_values) != 'array'){
							$valuesValid = false;
						}

					}
				}
			}

			return $valuesValid;

		}

		public static function salt($salt = ''){

			$saltValid = true;

			// if the salt is not set, fail
			if($salt == ''){
				$saltValid = false;
			}

			// if the salt is not an string, fail
			if(gettype($salt) != 'string' || strlen($salt) != 16){
				$saltValid = false;
			}

			return $saltValid;

		}

		public static function schema($schema = ''){

			$validFieldTypes = array(
				'html',
				'group',
				'input_text',
				'input_select',
				'input_single_checkbox',
				'input_text',
				'input_file',
				'input_textarea'
			);

			$schemaValid = true;

			// if the schema is not set, fail
			if($schema == ''){
				$schemaValid = false;
			}

			// if the schema is not an string, fail
			if(gettype($schema) != 'array'){
				$schemaValid = false;
			}

			if($schemaValid === true){
				if(count($schema) == 0){
					$schemaValid = false;
				} else {
					foreach ($schema as $key_schema => $value_schema) {
						if(!isset($value_schema['type']) || in_array($value_schema['type'], $validFieldTypes) === false ){
							$schemaValid = false;
						} else {
							if($value_schema['type'] == 'group'){
								if(!isset($value_schema['children'])){
									$schemaValid = false;
								} else {
									if(gettype($value_schema['children']) != 'array'){
										$schemaValid = false;
									} else {
										if(count($value_schema['children']) == 0){
											$schemaValid = false;
										} else {
											foreach ($value_schema['children'] as $key_children_schema => $value_children_schema) {
												if(!isset($value_children_schema['type']) || in_array($value_children_schema['type'], $validFieldTypes) === false ){
													$schemaValid = false;
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}

			return $schemaValid;

		}


	}