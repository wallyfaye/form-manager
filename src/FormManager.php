<?php

	namespace FormManager;

	use FormManager\Validate\Params;
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

	}