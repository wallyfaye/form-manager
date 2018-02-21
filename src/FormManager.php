<?php

	namespace FormManager;

	use FormManager\Validator\Params;
	use FormManager\Validator\Application;
	use FormManager\Validator\Submission;
	use FormManager\Hasher\Hash;
	use FormManager\Installer\InstallationManager;
	use FormManager\FileSystem\FolderManager;

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


		/**
		* Storage for form submissions
		*
		* @return boolean
		*/
		public function saveSubmission($submittedFields){
			$this->submittedFields = $submittedFields;
			$submission_dir = 'fm/submissions/' . $this->submissionHash;
			if(FolderManager::createUserDirectory($submission_dir)){

				$userSubmission = array();

				foreach ($this->submittedFields as $field_key => $field_value) {
					switch($field_value['schema']['type']){
						case 'input_file':
							$target_file = $submission_dir . '/' . $field_value['id'] . '.pdf';
							move_uploaded_file($field_value['data']['tmp_name'], $target_file);
							chmod($target_file, 0000);
						break;

						default:
							$userSubmission[$field_value['id']] = $field_value['data'];
						break;
					}
				}

				$userSubmissionJson = json_encode($userSubmission, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
				$target_file = $submission_dir . '/data.json';

				$fp = fopen($target_file, 'w');
				fwrite($fp, $userSubmissionJson);
				fclose($fp);
				chmod($target_file, 0000);

				return true;

			} else {

				return false;

			}
		}

		/**
		* Get data used to contstruct a form
		*
		* @return array
		*/
		public function extract($folderName = 'extract')
		{
			$extract_dir = $folderName . '/';
			$csv_file = $extract_dir . 'file.csv';
			mkdir($extract_dir, 0700);

			$header_row = array();
			$header_row[] = 'key';
			foreach ($this->fieldGroups as $fieldGroups_key => $fieldGroups_value) {
				foreach ($fieldGroups_value['fields'] as $fields_key => $fields_value) {
					if($fields_value['type'] != 'html'){
						$header_row[] = $fields_value['id'];
					}
				}
			}
			$export_array = array();
			$export_array[] = $header_row;

			foreach ($this->inputValues as $key => $value) {
				$user_dir = 'fm/submissions/' . $key;
				if(file_exists($user_dir)){
					$json_data = $user_dir . '/data.json';
					$dir_files = scandir($user_dir);
					foreach ($dir_files as $dir_files_key => $dir_files_value) {
						if($dir_files_value != '.' && $dir_files_value != '..'){
							$new_filename = $key . '_' . $dir_files_value;
							$new_filepath = $extract_dir . $new_filename;
							$existing_filepath = $user_dir . '/' . $dir_files_value;
							copy($existing_filepath, $new_filepath);
							chmod($new_filepath, 0600);
						}
					};
					$json = json_decode(file_get_contents($json_data), true);
					$user_row = array();
					foreach($header_row as $header_row_key => $header_row_value){
						if($header_row_value == 'key'){
							$user_row[] = $key;
						} else if(isset($json[$header_row_value])){
							$user_row[] = $json[$header_row_value];
						} else {
							$user_row[] = "";
						}
					}
					$export_array[] = $user_row;
				}
			}

			$fp = fopen($csv_file, 'w');
			chmod($csv_file, 0600);

			foreach ($export_array as $fields) {
			    fputcsv($fp, $fields);
			}

			fclose($fp);

		}

	}