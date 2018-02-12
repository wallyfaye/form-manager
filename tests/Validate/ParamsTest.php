<?php

	declare(strict_types=1);
	
	use PHPUnit\Framework\TestCase;
	use FormManager\Validate\Params;

	final class InstallDirTest extends TestCase
	{

		public function setUp() {
			$this->params = new Params();
		}

		/** @test
		 *	@covers FormManager\Validate\Params::installDir
		 */

		public function validate_params_for_install_directory(){

			$this->assertTrue($this->params->installDir(__DIR__), 'regular directory should be valid');
			$this->assertFalse($this->params->installDir(''), 'blank string should fail');
			$this->assertFalse($this->params->installDir(123), 'non strings should fail');
			$this->assertFalse($this->params->installDir('/'), 'trailing slashes should fail');

		}
	}