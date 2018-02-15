<?php

	namespace FormManager\Validate;

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
				foreach ($values as $key_values => $value_values) {
					// each value should be an array
					if(gettype($value_values) != 'array'){
						$valuesValid = false;
					}
					// // each value should have an id that is a string
					// if(!(isset($value_values['id']) && gettype($value_values['id']) == 'string')){
					// 	$valuesValid = false;
					// }
				}
			}

			return $valuesValid;

		}
	}