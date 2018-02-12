<?php

	namespace FormManager\Validate;

	class Params{

		/**
		* Validate installation directory
		*
		* @return boolean
		*/

		public function installDir($dir = ''){
			
			$dir_valid = true;

			// if the directory is not set, fail
			if($dir == ''){
				$dir_valid = false;
			}

			// if the directory is not a string, fail
			if(gettype($dir) != 'string'){
				$dir_valid = false;
			}

			// if the directory ends with a trailing slash, fail
			if(substr($dir, -1) == '/'){
				$dir_valid = false;
			}

			// if the directory does not exist and the parent is not writable, fail
			if(file_exists($dir) === false && is_writable(dirname($dir)) === false){
				$dir_valid = false;
			}

			// if the directory does exist and it is not writable, fail
			if(file_exists($dir) === true && is_writable($dir) === false){
				$dir_valid = false;
			}

			return $dir_valid;

		}

	}