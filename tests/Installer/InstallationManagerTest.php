<?php
	declare(strict_types=1);
	
	use PHPUnit\Framework\TestCase;
	use FormManager\Installer\InstallationManager;
	use org\bovigo\vfs\vfsStream;
	use org\bovigo\vfs\vfsStreamWrapper;
	use org\bovigo\vfs\vfsStreamDirectory;

	final class InstallationManagerTest extends TestCase
	{

		public function setUp()
		{
			$this->main_dir = 'form_manager_root';
			vfsStreamWrapper::register();
			vfsStreamWrapper::setRoot(new vfsStreamDirectory($this->main_dir));
		}

		/** @test
		 *	@covers FormManager\Installer\InstallationManager::doInstall
		 */

		public function failing_install_setup(){

			$this->assertFalse(vfsStreamWrapper::getRoot()->hasChild('fm'), 'directory should not exist');

			$im = new InstallationManager();
			$this->assertFalse($im->doInstall(), 'install should have failed');
			$this->assertFalse($im->doInstall('fm'), 'install should have failed');
			$this->assertFalse($im->doInstall('/fm'), 'install should have failed');
			$this->assertFalse($im->doInstall('fm/fm'), 'install should have failed');
			$this->assertFalse($im->doInstall('/fm/fm'), 'install should have failed');

			$this->assertFalse(vfsStreamWrapper::getRoot()->hasChild('fm'), 'directory should not exist');

		}

		/** @test
		 *	@covers FormManager\Installer\InstallationManager::doInstall
		 */

		public function succeeding_install_setup(){

			$this->assertFalse(vfsStreamWrapper::getRoot()->hasChild('fm'), 'directory should not exist');

			$im = new InstallationManager();
			$this->assertTrue($im->doInstall(vfsStream::url($this->main_dir)), 'install should have succeeded');

			$this->assertTrue(vfsStreamWrapper::getRoot()->hasChild('fm'), 'main directory should exist');
			$this->assertEquals(0300, vfsStreamWrapper::getRoot()->getChild('fm')->getPermissions(), 'main directory should only have write permissions');

			$this->assertTrue(vfsStreamWrapper::getRoot()->getChild('fm')->hasChild('submissions'), 'submissions directory should exist');
			$this->assertEquals(0300, vfsStreamWrapper::getRoot()->getChild('fm')->getChild('submissions')->getPermissions(), 'submissions directory should only have write permissions');

		}

		/** @test
		 *	@covers FormManager\Installer\InstallationManager::checkInstall
		 */

		public function check_for_installation_status(){

			$im = new InstallationManager();
			$this->assertEquals('no_install', $im->checkInstall(vfsStream::url($this->main_dir)), 'the installation should not exist');
			$this->assertTrue($im->doInstall(vfsStream::url($this->main_dir)), 'install should have succeeded');
			$this->assertEquals('installed', $im->checkInstall(vfsStream::url($this->main_dir)), 'an installation should have been found');

			vfsStreamWrapper::getRoot()->getChild('fm')->getChild('submissions')->chmod(0777);
			$this->assertEquals('bad_install', $im->checkInstall(vfsStream::url($this->main_dir)), 'the installation should have permission problems');
			vfsStreamWrapper::getRoot()->getChild('fm')->getChild('submissions')->chmod(0300);

			vfsStreamWrapper::getRoot()->getChild('fm')->chmod(0777);
			$this->assertEquals('bad_install', $im->checkInstall(vfsStream::url($this->main_dir)), 'the installation should have permission problems');

			vfsStreamWrapper::getRoot()->getChild('fm')->removeChild('submissions');
			$this->assertEquals('bad_install', $im->checkInstall(vfsStream::url($this->main_dir)), 'the installation should have permission problems');


		}


	}