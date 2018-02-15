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

	}