<?php

	namespace FormManager\Validator;

	class Submission{

		public $validKeys = array();
		public $requiredKeys = array();
		public $validSubmission = false;

		/**
		* Validate submission keys against schema
		*
		* @return void
		*/

		public function keys($postArgs = array(), $schema = array()){

			foreach ($schema as $formSchema_key => $formSchema_value) {
				if(isset($formSchema_value['type'])){
					if($formSchema_value['type'] == 'group'){
						if(isset($formSchema_value['children'])){
							foreach ($formSchema_value['children'] as $formSchema_children_key => $formSchema_children_value) {
								$this->validKeys[] = $formSchema_children_value['key'];
								if(isset($formSchema_children_value['features']['validation']['required']) && $formSchema_children_value['features']['validation']['required'] == true){
									$this->requiredKeys[] = $formSchema_children_value['key'];
								}
							}
						}
					} else {
						$this->validKeys[] = $formSchema_value['key'];
						if(isset($formSchema_value['features']['validation']['required']) && $formSchema_value['features']['validation']['required'] == true){
							$this->requiredKeys[] = $formSchema_value['key'];
						}
					}
				}
			}

			$validSubmission = true;
			foreach ($this->requiredKeys as $key => $value) {
				if(!isset($postArgs[$value])){
					$validSubmission = false;
				}
			}

			$this->validSubmission = $validSubmission;

		}

	}