<?php

	namespace FormManager\Hasher;

	class Hash{

		/**
		* @var string $hashType indicates what hashing function is used
		*/
		private $hashType = '';
		private $salt = '';

		/**
		* Class constructor
		*
		* @return void
		*/
		public function __construct($type = '', $salt = '') 
		{
			$this->hashType = $type;
			$this->salt = $salt;
		}

		/**
		* Hash value generator
		*
		* @return string
		*/

		public function generate($value = ''){
			$hash = '';
			switch ($this->hashType) {
				case 'input':
					if($value != ''){
						$hash = $this->input_hash($value);
					}
				break;
			}

			return $hash;

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