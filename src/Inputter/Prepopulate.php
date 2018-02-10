<?php

namespace FormManager\Inputter;

	class Prepopulate{

		public $salt;

		public function __construct($params = null){
			$this->salt = $params['salt'];
		}

	}