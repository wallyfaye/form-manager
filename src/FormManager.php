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

			if(!isset($params['fieldGroups']) || !$paramsValidator->fields($params['fieldGroups'])){
				$paramsValid = false;
			} else {
				$this->fieldGroups = $params['fieldGroups'];
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
				$this->submissionHash = $hashed_value;
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
				$formData['fieldGroups'] = $this->fieldGroups;
			}
			return $formData;
		}

		/**
		* Checks if form was submitted properly
		*
		* @return boolean
		*/
		public function validateSubmission($postData = array(), $fileData = array())
		{

			$submitted = false;
			$submissionValid = true;
			$validatedFields = array();

			foreach ($this->fieldGroups as $group_key => $group_value) {
				foreach ($group_value['fields'] as $field_key => $field_value) {

					$field_valid = true;
					$field_id = $field_value['id'];
					$is_field_declared = isset($postData[$field_id]);
					$is_file_declared = isset($fileData[$field_id]);
					$is_required = isset($field_value['required']) && $field_value['required'];
					$is_validation_set = isset($field_value['validation']);

					if(!$is_field_declared && !$is_file_declared && $is_required){

						$field_valid = false;

					} else if($is_field_declared){

						$is_field_empty = $postData[$field_id] == '';

						if($is_required && $is_field_empty){
							$field_valid = false;
						}

						if($field_valid && $is_validation_set && !$is_field_empty){

							if(!Submission::fieldTypeValidator($field_value['validation'], $postData[$field_id])){
								$field_valid = false;
							}
						}

					} else if ($is_file_declared){

						$is_field_empty = $fileData[$field_id]['name'] == '';

						if($is_required && $is_field_empty){
							$field_valid = false;
						}

						if($field_valid && $is_validation_set && !$is_field_empty){

							if(!Submission::fieldTypeValidator($field_value['validation'], $fileData[$field_id])){
								$field_valid = false;
							}
						}

					}

					if($field_valid){

						if($is_field_declared){
							$validatedFields[] = array(
								'id' => $field_id,
								'data' => $postData[$field_id],
								'schema' => $field_value
							);
						} else if ($is_file_declared){

							$validatedFields[] = array(
								'id' => $field_id,
								'data' => $fileData[$field_id],
								'schema' => $field_value
							);

						}

					} else {

						$submissionValid = false;

					}

				}
			}

			if($submissionValid){
				if($this->saveSubmission($validatedFields)){
					$submitted = true;
				}
			}

			return $submissionValid && $submitted;
		}

		public function saveSubmission($submittedFields){
			$this->submittedFields = $submittedFields;
			echo '<pre>';
			print_r($this->submissionHash);
			echo '</pre>';
			echo '---<br />';
			echo '<pre>';
			print_r($this->submittedFields);
			echo '</pre>';
			return true;
		}

	}