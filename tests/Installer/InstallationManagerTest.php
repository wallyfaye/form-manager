<?php
	declare(strict_types=1);
	
	use PHPUnit\Framework\TestCase;
	use FormManager\Installer\InstallationManager;

	final class InstallationManagerTest extends TestCase
	{
		/** @test
		 *	@covers FormManager\Validate\Params::directory
		 */

		public function basic_install_setup(){

			$this->assertInstanceOf(InstallationManager::class, new InstallationManager(), 'InstallationManager should be an instance of itself');

		}
	}