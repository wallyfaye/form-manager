<?php

namespace FormManager;

	use FormManager\Validate\Params;

	class FormManager{

		/**
		* @var boolean $paramsValid Indicates if FormManager was instantiated correctly
		*/
		public $paramsValid;

		/**
		* Class constructor
		*
		* @return void
		*/
		public function __construct($params = array()) 
		{
			if($this->validateParams($params)){
				$this->paramsValid = true;
			} else {
				$this->paramsValid = false;
			}
		}

		/**
		* Class constructor
		*
		* @return boolean
		*/
		private function validateParams($params = array())
		{

			$paramsValid = true;

			$paramsValidator = new Params();

			if(!isset($params['installDir']) || !$paramsValidator->installDir($params['installDir'])){
				$paramsValid = false;
			} else {
				$this->installDir = $params['installDir'];
			}

			return $paramsValid;

		}

	}