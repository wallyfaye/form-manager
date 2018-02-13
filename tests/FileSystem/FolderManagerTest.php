<?php

	declare(strict_types=1);
	
	use PHPUnit\Framework\TestCase;
	use FormManager\FileSystem\FolderManager;
	use org\bovigo\vfs\vfsStream;
	use org\bovigo\vfs\vfsStreamWrapper;
	use org\bovigo\vfs\vfsStreamDirectory;

	final class FolderManagerTest extends TestCase
	{

		public function setUp()
		{
			$this->main_dir = 'fm_dir';
			vfsStreamWrapper::register();
			vfsStreamWrapper::setRoot(new vfsStreamDirectory($this->main_dir));
		}

		/** @test
		 *	@covers FormManager\FileSystem\FolderManager::createDirectory
		 */

		public function validate_params_for_install_directory()
		{

			$this->folderManager = new FolderManager();
			$this->assertFalse($this->folderManager->createDirectory(), 'missing parameters should fail');

		}

		/** @test
		 *	@covers FormManager\FileSystem\FolderManager::createDirectory
		 */

		public function create_directory()
		{
			$fm = new FolderManager();
			$this->assertFalse(vfsStreamWrapper::getRoot()->hasChild('demo'), 'direcoty should not exist');

			$fm->createDirectory(vfsStream::url($this->main_dir) . '/' . 'demo');
			$this->assertTrue(vfsStreamWrapper::getRoot()->hasChild('demo'), 'directory should exist');
		}
	}