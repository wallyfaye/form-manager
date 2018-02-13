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
		 *	@covers FormManager\Validate\Params::directory
		 */

		public function validate_params_for_install_directory(){

			$this->assertTrue($this->params->directory(__DIR__), 'existing directory should be valid because it is writeable');
			$this->assertFalse($this->params->directory(''), 'blank string should fail');
			$this->assertFalse($this->params->directory(123), 'non strings should fail');
			$this->assertFalse($this->params->directory('test/'), 'trailing slashes should fail');
			$this->assertFalse($this->params->directory('/var'), 'directories without permissions should fail');
			$this->assertTrue($this->params->directory('test'), 'directories that do not exist with writeable parents should pass');
			$this->assertFalse($this->params->directory('test/test'), 'directories with parents that are not writeable should fail');

		}
	}