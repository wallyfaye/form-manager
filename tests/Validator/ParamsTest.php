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
		 *	@covers FormManager\Validator\Params::fields
		 */

		public function validate_params_for_install_fields(){

			$validFieldGroups = array(
				array(
					'duplicatable' => true,
					'fields' => array(
						array(
							'id' => 'first_name',
							'field_text' => 'First Name',
							'type' => 'input_text',
							'required' => true,
							'validation' => 'email'
						),
						array(
							'id' => 'last_name',
							'field_text' => 'Last Name',
							'type' => 'input_text',
							'required' => true,
						)
					)
				)
			);

			$this->assertFalse($this->params::fields(), 'blank fields are not valid');

			$this->assertFalse($this->params::fields(123), 'non array are not valid');

			$this->assertFalse($this->params::fields(
				array(
				)
			), 'empty arrays are not valid');

			$this->assertFalse($this->params::fields(
				array(
					array()
				)
			), 'arrays with empty arrays are not valid');

			$this->assertFalse($this->params::fields(
				array(
					array('test')
				)
			), 'each array should have a fields key');

			$this->assertFalse($this->params::fields(
				array(
					array(
						'fields' => 123
					)
				)
			), 'fields must be arrays');
			
			$this->assertFalse($this->params::fields(
				array(
					array(
						'fields' => array()
					)
				)
			), 'fields cannot be an empty array');

			$this->assertFalse($this->params::fields(
				array(
					array(
						'fields' => array(
							123
						)
					)
				)
			), 'fields cannot be composed of non arrays');

			$this->assertFalse($this->params::fields(
				array(
					array(
						'fields' => array(
							array()
						)
					)
				)
			), 'fields cannot be composed of empty field arrays');

			$this->assertFalse($this->params::fields(
				array(
					array(
						'fields' => array(
							array(123)
						)
					)
				)
			), 'field definitions must have an id key');

			$this->assertFalse($this->params::fields(
				array(
					array(
						'fields' => array(
							array(
								'id' => 123
							)
						)
					)
				)
			), 'field definitions must have string id keys');

			$this->assertFalse($this->params::fields(
				array(
					array(
						'fields' => array(
							array(
								'id' => 'test'
							)
						)
					)
				)
			), 'field definitions must have type key');			

			$this->assertFalse($this->params::fields(
				array(
					array(
						'fields' => array(
							array(
								'id' => 'test',
								'type' => 'random_type'
							)
						)
					)
				)
			), 'field definitions must have a valid type');

			$this->assertTrue($this->params::fields($validFieldGroups), 'properly formatted fields definitions are valid');

		}


	}