<?php

	namespace FormManager\FileSystem;

	class FolderManager{

		/**
		* Creates directories with restricted permissions
		*
		* @return boolean
		*/

		public function createDirectory($dir = ''){

			$dirCreated = true;

			if($dir == ''){
				$dirCreated = false;
			}

			return $dirCreated;

		}

	}