<?php

namespace FormManager;

	use FormManager\Validate\Params;

	class FormManager{

		/**
		* Class constructor
		*
		* @return void
		*/
		public function __construct($params = array()) 
		{
			$this->validate_params($params);
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