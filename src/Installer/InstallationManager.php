<?php

	namespace FormManager\Installer;
	use FormManager\FileSystem\FolderManager;

	class InstallationManager{

		/**
		* Creates setup needed for Form Manager
		*
		* @return boolean
		*/

		public static function doInstall($root_dir = ''){

			$didInstall = false;

			$main_fm_dir = '/fm';
			$submission_dir = $main_fm_dir . '/submissions';

			if(FolderManager::createUserDirectory($root_dir . $main_fm_dir)){
				if(FolderManager::createUserDirectory($root_dir . $submission_dir)){
					$didInstall = true;
				}
			}

			return $didInstall;

		}


	}