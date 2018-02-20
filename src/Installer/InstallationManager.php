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

		/**
		* Check a directory for an installation
		* can be: no_install, bad_install, or installed
		*
		* @return string
		*/

		public static function checkInstall($root_dir = ''){

			$main_fm_dir = '/fm';
			$submission_dir = $main_fm_dir . '/submissions';

			$path_main_fm = $root_dir . $main_fm_dir;
			$path_submission = $root_dir . $submission_dir;

			if(
				is_dir($path_main_fm) &&
				is_dir($path_submission)
			){
				if(
					substr(sprintf('%o', fileperms($path_main_fm)), -4) == '0300' &&
					substr(sprintf('%o', fileperms($path_submission)), -4) == '0300'
				){
					return 'installed';
				} else {
					return 'bad_install';
				}
			} else if (
				is_dir($path_main_fm) ||
				is_dir($path_submission)
			){
				return 'bad_install';
			} else {
				return 'no_install';
			}

		}




	}