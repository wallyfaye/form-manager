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

	}