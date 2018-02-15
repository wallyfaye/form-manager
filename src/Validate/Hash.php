<?php

	namespace FormManager\Validate;

	class Hash{

		/**
		* @var string $hashType indicates what hashing function is used
		*/
		private $hashType = '';

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
		* Hash values for validation
		*
		* @return boolean
		*/

		public function validate($hash = ''){
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
		* @return boolean
		*/

		private function input_hash($hash_provided){
			return true;
		}

	}