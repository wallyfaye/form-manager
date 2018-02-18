<?php

	declare(strict_types=1);
	
	use PHPUnit\Framework\TestCase;
	use FormManager\Validator\Params;

	final class InstallDirTest extends TestCase
	{

		public function setUp() {
			$this->params = new Params();
		}

		/** @test
		 *	@covers FormManager\Validator\Params::directory
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

		/** @test
		 *	@covers FormManager\Validator\Params::values
		 */

		public function validate_params_for_install_values(){

			$this->assertFalse($this->params::values(), 'blank values are not valid');
			$this->assertFalse($this->params::values(array()), 'empty arrays are not valid');
			$this->assertTrue($this->params::values(array('valid' => array())), 'arrays should be nested');

		}

		/** @test
		 *	@covers FormManager\Validator\Params::salt
		 */

		public function validate_params_for_install_salt(){

			$this->assertFalse($this->params::salt(), 'blank salt are not valid');
			$this->assertFalse($this->params::salt(array()), 'non strings are not valid');
			$this->assertTrue($this->params::salt('1234567890123456'), '16 digit strings are valid');

		}

		/** @test
		 *	@covers FormManager\Validator\Params::schema
		 */

		public function validate_params_for_install_schema(){

			$this->assertFalse($this->params::schema(), 'blank schema is not valid');
			$this->assertFalse($this->params::schema('123'), 'non arrays are not valid');
			$this->assertFalse($this->params::schema(array()), 'empty arrays are not valid');
			$this->assertFalse($this->params::schema(array(array('type' => 'abc'))), 'types that are not recognized are not valid');
			$this->assertFalse($this->params::schema(array(array('type' => 'group'))), 'type group without children set is not valid');
			$this->assertFalse($this->params::schema(array(array('type' => 'group', 'children' => 123))), 'type group with children that is not an array is not valid');
			$this->assertFalse($this->params::schema(array(array('type' => 'group', 'children' => array()))), 'type group with children that is an empty array is not valid');

			$invalidSchema = array(
				'test0' => array(
					'type' => 'group', 
					'children' => array(
						'test1' => array(
							'type' => 'abc'
						)
					)
				)
			);
			$this->assertFalse($this->params::schema($invalidSchema), 'type group with children that is an invalid type is not valid');

			$validSchema = array(
				'test0' => array(
					'type' => 'group', 
					'children' => array(
						'test1' => array(
							'type' => 'html'
						)
					)
				)
			);
			$this->assertTrue($this->params::schema($validSchema), 'nested schema should validate');
		}

	}