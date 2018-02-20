<?php

	namespace FormManager\Validator;

	class Submission{

		public static function fieldTypeValidator($validationType, $value){

			$fieldValid = true;

			switch ($validationType) {
				case 'email':
					$fieldValid = filter_var($value, FILTER_VALIDATE_EMAIL);
					break;
				
				case 'pdf':
					$finfo = finfo_open(FILEINFO_MIME_TYPE);
					$finfo_file_type = finfo_file($finfo, $value['tmp_name']);
					finfo_close($finfo);

					$is_valid_finfo = 'application/pdf' == $finfo_file_type;
					$is_valid_type = 'application/pdf' == $value['type'];
					$is_pdf = 'pdf' == pathinfo($value['name'])['extension'];
					$is_within_size_limit = 1000000 >= $value['size'];
					$is_error_free = 0 == $value['error'];

					$fieldValid = ($is_valid_finfo && $is_valid_type && $is_pdf && $is_within_size_limit && $is_error_free);
					break;
				
			}

			return true;
		}

	}