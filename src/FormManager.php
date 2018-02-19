<?php

	namespace FormManager;

	use FormManager\Validator\Params;
	use FormManager\Validator\Application;
	use FormManager\Validator\Submission;
	use FormManager\Hasher\Hash;
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

			if(!isset($params['inputSalt']) || !$paramsValidator->salt($params['inputSalt'])){
				$paramsValid = false;
			} else {
				$this->inputSalt = $params['inputSalt'];
			}

			if(!isset($params['formSchema']) || !$paramsValidator->schema($params['formSchema'])){
				$paramsValid = false;
			} else {

				$formSchema = array();
				foreach ($params['formSchema'] as $formSchema_key => $formSchema_value) {
					$formSchema_value['key'] = $formSchema_key;
					if($formSchema_value['type'] == 'group'){
						foreach ($formSchema_value['children'] as $formSchema_children_key => $formSchema_children_value) {
							$formSchema_value['children'][$formSchema_children_key]['key'] = $formSchema_children_key;
						}
					}
					$formSchema[] = $formSchema_value;
				}
				$this->formSchema = $formSchema;

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
			$valid_hash = false;
			$vh = new Hash($type, $this->inputSalt);
			$hashed_value = $vh->generate($hash);
			if(isset($this->inputValues[$hashed_value])){
				$this->inputValue = $this->inputValues[$hashed_value];
				$valid_hash = true;
			}
			return $valid_hash;
		}

		/**
		* Get data used to contstruct a form
		*
		* @return array
		*/
		public function getFormData()
		{
			$formData = array();
			if($this->paramsValid){
				$formData['inputValue'] = $this->inputValue;
				$formData['formSchema'] = $this->formSchema;

			}
			return $formData;
		}

		/**
		* Handle form submissions
		*
		* @return array
		*/
		public function processSubmission($formPost){

			$submission = new Submission();
			$submission->keys($formPost, $this->formSchema);

			if($submission->validSubmission){
				foreach ($submission->validKeys as $key => $value) {
					if(isset($formPost[$value])){
						print_r($value . ', ' . $formPost[$value] . '<br />');
					}
				}
			} else {
				echo 'bad submission';
			}


		}

	}