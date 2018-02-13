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
		 *	@covers FormManager\FileSystem\FolderManager::createUserDirectory
		 */

		public function validate_params_for_install_directory()
		{

			$this->assertFalse(FolderManager::createUserDirectory(), 'missing parameters should fail');

		}

		/** @test
		 *	@covers FormManager\FileSystem\FolderManager::createUserDirectory
		 */

		public function read_write_dir_create_and_permissions()
		{

			$directory_to_test = 'demo_dir';

			$this->assertFalse(FolderManager::createUserDirectory(123), 'non strings should fail');
			$this->assertFalse(FolderManager::createUserDirectory('/var'), 'directories without permissions should fail');
			$this->assertFalse(FolderManager::createUserDirectory('test/'), 'trailing slashes should fail');
			$this->assertFalse(FolderManager::createUserDirectory(''), 'blank string should fail');

			$this->assertFalse(vfsStreamWrapper::getRoot()->hasChild($directory_to_test), 'directory should not exist');
			$this->assertTrue(FolderManager::createUserDirectory(vfsStream::url($this->main_dir) . '/' . $directory_to_test), 'directory should have been created');
			$this->assertTrue(vfsStreamWrapper::getRoot()->hasChild($directory_to_test), 'directory should exist');
			$this->assertEquals(0700, vfsStreamWrapper::getRoot()->getChild($directory_to_test)->getPermissions(), 'directory should only have write permissions');

		}
	}