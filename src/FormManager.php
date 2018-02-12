<?php

namespace FormManager;

	use FormManager\Validate\Params;
	use FormManager\FileSystem\FolderManager;

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
				$this->doInstall();
			} else {
				$this->paramsValid = false;
			}
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

			if(!isset($params['installDir']) || !$paramsValidator->installDir($params['installDir'])){
				$paramsValid = false;
			} else {
				$this->installDir = $params['installDir'];
			}

			return $paramsValid;

		}

		/**
		*
		* @return boolean 
		*/
		private function doInstall(){
			$folderManager = new FolderManager();
			$folderManager->createDirectory($this->installDir . '/fm');
			$folderManager->createDirectory($this->installDir . '/fm' . '/submissions');
			$folderManager->createDirectory($this->installDir . '/fm' . '/reports');
			echo 'install complete';
		}

	}