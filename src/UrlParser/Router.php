<?php

	namespace FormManager\UrlParser;

	class Router{
		private $application_modes = array(
			'i' => null,
			'o' => null
		);

		public function getApplicationMode(){

			echo PHP_EOL;
				echo 'ok';
			echo PHP_EOL;
			// $application_mode = 'none';
			// $mode_is_set = isset($_GET['m']);
			// $mode_length_valid = strlen($_GET['m']);
			// $mode_type_valid = $this->application_modes($_GET['m']) == null;
			// if($mode_is_set && $mode_length_valid && $mode_type_valid){
			// 	echo 'ok';
			// } else {
			// 	echo 'no';
			// }
		}
	}