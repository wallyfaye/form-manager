<?php

namespace FormManager;

	use FormManager\Validate\Params;

	class FormManager{

		/**
		* @var string
		*/
		private $installDir = '';

		/**
		* Class constructor
		*
		* @return void
		*/
		public function __construct($params = array()) 
		{
			if($this->validate_params($params)){
				echo 'params ok';
			} else {
				echo 'params bad';
			}
		}

		/**
		* Class constructor
		*
		* @return boolean
		*/
		private function validate_params($params = array())
		{

			$params_valid = true;

			$params_validator = new Params();

			if(!isset($params['installDir']) || !$params_validator->installDir($params['installDir'])){
				$params_valid = false;
			}

			return $params_valid;

		}

	}