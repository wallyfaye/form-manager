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

		/**
		* Validate input values
		*
		* @return boolean
		*/

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

		/**
		* Validate application salt
		*
		* @return boolean
		*/

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

		/**
		* Validate fields
		*
		* @return boolean
		*/

		public static function fields($fields = array()){

			$validFieldTypes = array(
				'html',
				'input_text',
				'input_select',
				'input_single_checkbox',
				'input_file',
				'input_textarea'
			);

			$fieldsValid = true;

			if(count($fields) == 0){
				$fieldsValid = false;
			}

			if($fieldsValid && gettype($fields) != 'array'){
				$fieldsValid = false;
			}

			if($fieldsValid){
				foreach ($fields as $fields_key => $fields_value) {
					if($fieldsValid && count($fields_value) == 0){
						$fieldsValid = false;
					}
					if($fieldsValid && !isset($fields_value['fields'])){
						$fieldsValid = false;
					}
					if($fieldsValid && gettype($fields_value['fields']) != 'array'){
						$fieldsValid = false;
					}
					if($fieldsValid && count($fields_value['fields']) == 0){
						$fieldsValid = false;
					}
					if($fieldsValid){
						foreach ($fields_value['fields'] as $field_item_key => $field_item_value) {
							if($fieldsValid && gettype($field_item_value) != 'array'){
								$fieldsValid = false;
							}
							if($fieldsValid && count($field_item_value) == 0){
								$fieldsValid = false;
							}
							if($fieldsValid && !isset($field_item_value['id'])){
								$fieldsValid = false;
							}
							if($fieldsValid && gettype($field_item_value['id']) != 'string'){
								$fieldsValid = false;
							}
							if($fieldsValid && !isset($field_item_value['type'])){
								$fieldsValid = false;
							}
							if($fieldsValid && !in_array($field_item_value['type'], $validFieldTypes)){
								$fieldsValid = false;
							}
							// if($fieldsValid && !in_array($field_item_value['id'], $validFieldTypes)){
							// 	$fieldsValid = false;
							// }
						}
					}
				}
			}

			return $fieldsValid;

		}

	}