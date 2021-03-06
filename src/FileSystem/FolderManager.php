<?php

	namespace FormManager\FileSystem;
	use FormManager\Validator\Params;

	class FolderManager{

		/**
		* Creates directories with restricted permissions
		*
		* @return boolean
		*/

		public static function createUserDirectory($dir = false){
			
			$dir_created = false;

			if(!Params::directory($dir)){
				return $dir_created;
			}

			if (!file_exists($dir)) {
				if(@mkdir($dir, 0300, true)){
					$dir_created = true;
				}
			}

			return $dir_created;

		}

	}