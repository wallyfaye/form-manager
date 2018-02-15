<?php

	namespace FormManager\Validate;

	class Hash{

		/**
		* @var string $hashType indicates what hashing function is used
		*/
		private $hashType = '';
		private $salt = '1234567890123456';

		/**
		* Class constructor
		*
		* @return void
		*/
		public function __construct($type = '') 
		{
			$this->hashType = $type;
		}

		/**
		* Hash value generator
		*
		* @return boolean
		*/

		public function generate($hash = ''){
			$hash_valid = false;
			switch ($this->hashType) {
				case 'input':
					if($hash != ''){
						$hash_valid = $this->input_hash($hash);
					}
				break;
				
				default:
					break;
			}

			return $hash_valid;

		}

		/**
		* Test input hash
		*
		* @return string
		*/

		private function input_hash($hash_provided){

			$salt = '$6$rounds=5000$' . $this->salt;
			$crypt_value = crypt($hash_provided, $salt);
			$crypt_value = str_replace($salt, "", $crypt_value);
			$crypt_value = preg_replace("/[^a-zA-Z0-9]+/", "", $crypt_value);
			return $crypt_value;

		}

	}