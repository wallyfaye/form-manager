<?php

	namespace FormManager\Validate;

	class Params{

		public $salt = 123;

		/**
		* Class constructor
		*
		* @return void
		*/

		public function __construct(){
		}

		/**
		* Validate installation directory
		*
		* @return boolean
		*/

		public function installDir($dir = ''){
			$dir_valid = true;
			if($dir == ''){
				$dir_valid = false;
			}
			if(gettype($dir) != 'string'){
				$dir_valid = false;
			}
			if(substr($dir, -1) == '/'){
				$dir_valid = false;
			}
			return $dir_valid;
		}

	}