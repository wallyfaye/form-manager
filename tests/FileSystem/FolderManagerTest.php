<?php

	declare(strict_types=1);
	
	use PHPUnit\Framework\TestCase;
	use FormManager\FileSystem\FolderManager;

	final class FolderManagerTest extends TestCase
	{

		public function setUp() {
			$this->folderManager = new FolderManager();
		}

		/** @test
		 *	@covers FormManager\FileSystem\FolderManager::createDirectory
		 */

		public function validate_params_for_install_directory(){

			$this->assertFalse($this->folderManager->createDirectory(), 'missing parameters should fail');

		}
	}