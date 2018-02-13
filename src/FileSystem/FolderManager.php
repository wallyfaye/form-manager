<?php

	namespace FormManager\FileSystem;

	class FolderManager{

		/**
		* Creates directories with restricted permissions
		*
		* @return boolean
		*/

		public function createReadWriteDirectory($dir = false){

			if($dir === false){
				return false;
			}

			if (!file_exists($dir)) {
				if(@mkdir($dir, 0600, true)){
					return true;
				}
			}

		}

	}