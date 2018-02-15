<?php

	namespace FormManager;

	use FormManager\Validate\Params;
	use FormManager\Validate\Application;
	use FormManager\Validate\Hash;
	use FormManager\Installer\InstallationManager;

	class FormManager{

		/**
		* @var boolean $paramsValid Indicates if FormManager was instantiated correctly
		*/
		private $paramsValid = false;

		/**
		* Class constructor
		*
		* @return void
		*/
		public function __construct($params = array()) 
		{
			$this->validateParams($params);
		}

		/**
		* Check is params are valid
		*
		* @return boolean
		*/
		private function validateParams($params = array())
		{

			$paramsValid = true;

			$paramsValidator = new Params();

			if(!isset($params['installDir']) || !$paramsValidator->directory($params['installDir'])){
				$paramsValid = false;
			} else {
				$this->installDir = $params['installDir'];
			}

			if(!isset($params['inputValues']) || !$paramsValidator->values($params['inputValues'])){
				$paramsValid = false;
			} else {
				$this->inputValues = $params['inputValues'];
			}

			if($paramsValid){
				$this->paramsValid = true;
			}

			return $paramsValid;

		}

		/**
		* Install Form Manager
		*
		* @return string
		*/
		public function install()
		{
			if($this->paramsValid){
				$install_status = InstallationManager::checkInstall($this->installDir);
				switch ($install_status) {
					case 'no_install':
						if(InstallationManager::doInstall($this->installDir)){
							return 'installed';
						} else {
							return 'install_failed';
						}
					break;
					
					case 'installed':
						return 'installed';
					break;
					
					case 'bad_install':
						return 'bad_install';
				}
			}
			
			return 'install_skipped';
			
		}

		/**
		* Determines the mode to run the application in
		*
		* @return boolean
		*/
		public function validateApplicationMode($mode = 'n')
		{
			$valid_run_mode = false;

			if($this->paramsValid){
				$valid_run_mode = Application::mode($mode);
			}

			return $valid_run_mode;
		}

		/**
		* Valid hashes provided
		*
		* @return boolean
		*/
		public function validateHash($hash='', $type='')
		{
			$valid_hash = true;
			$vh = new Hash($type);
			$hashed_value = $vh->generate($hash);
			if(isset($this->inputValues[$hashed_value])){
				echo $hashed_value;
				echo '<br />';
				print_r($this->inputValues[$hashed_value]);
				echo '<br />';
			} else {
				$valid_hash = false;
			}
			return $valid_hash;
		}

	}