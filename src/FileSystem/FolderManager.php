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
				return false;
			}

			if (!file_exists($dir)) {
				mkdir($dir, 0700, true);
			}

			return $dirCreated;

		}

	}