<?php

	namespace FormManager\Validator;

	class Application{

		/**
		* Validate application mode
		*
		* @return boolean
		*/

		public static function mode($mode = 'n'){

			$valid_run_mode = false;

			switch ($mode) {
				case 'i':
					$valid_run_mode = true;
					break;
				
				case 'o':
					$valid_run_mode = true;
					break;
				
				case 'e':
					$valid_run_mode = true;
					break;
				
				default:
					break;
			}

			return $valid_run_mode;

		}

	}