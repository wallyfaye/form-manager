<?php

namespace FormManager;

	use FormManager\Validate\Params;

	class FormManager{

		/**
		* @var boolean $params_valid Indicates if FormManager was instantiated correctly
		*/
		private $params_valid;

		/**
		* Class constructor
		*
		* @return void
		*/
		public function __construct($params = array()) 
		{
			if($this->validate_params($params)){
				$this->params_valid = true;
			} else {
				$this->params_valid = false;
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
			} else {
				$this->installDir = $params['installDir'];
			}

			return $params_valid;

		}

	}