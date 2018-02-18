<?php  

	declare(strict_types=1);
	
	use PHPUnit\Framework\TestCase;
	use FormManager\FormManager;
	use org\bovigo\vfs\vfsStream;
	use org\bovigo\vfs\vfsStreamWrapper;
	use org\bovigo\vfs\vfsStreamDirectory;

	final class FormManagerTest extends TestCase
	{

		public function setUp()
		{
			$this->valid_params = array(
				'formSchema' => array(
					'test1' => array(
						'type' => 'html'
					),
					'test2' => array(
						'type' => 'group',
						'children' => array(
							'first_name' => array(
								'type' => 'html'
							)
						)
					)
				),
				'installDir' => __DIR__,
				'inputSalt' => '1234567890123456',
				'inputValues' => array(
					'NovstOCKb5aHffYvehwMkAjyZQ5TZYW631AMhVAsgev4ysN0HIHhD2dX2k0MidsuqYugyHzKOeRGebmrVpp' => array(
						'first_name' => 'John',
						'last_name' => 'Doe'
						)
					)
				);
			$this->invalid_params = array();
		}

		/** @test
		 *	@covers FormManager\FormManager
		 */

		public function instantiate_form_manager(){

			// set test class and method
				$testClass = '\FormManager\FormManager';
				$testMethod = 'validateParams';

			// create mock of class
				$fm = $this->getMockBuilder($testClass)
					->getMock();

			// do test
				$this->assertInstanceOf(FormManager::class, $fm, 'FormManager should be an instance of itself');

		}

		/** @test
		 *	@covers FormManager\FormManager::validateParams
		 */

		public function validate_parameters()
		{

			// set test class and method
				$testClass = '\FormManager\FormManager';
				$testMethod = 'validateParams';

			// create mock of class
				$fm = $this->getMockBuilder($testClass)
					->setMethods([$testMethod])
					->disableOriginalConstructor()
					->getMock();

			// create reflection
				$reflectedClass = new ReflectionClass(get_class($fm));

			// make private method accessible
				$method = $reflectedClass->getMethod($testMethod);
				$method->setAccessible(true);

				$this->assertTrue($method->invoke($fm, $this->valid_params), 'valid params should validate');
				$this->assertFalse($method->invoke($fm, $this->invalid_params), 'invalid params should not validate');

		}

		/** @test
		 *	@covers FormManager\FormManager::install
		 */

		public function verify_installation(){

			$this->main_dir = 'form_manager_root';
			vfsStreamWrapper::register();
			vfsStreamWrapper::setRoot(new vfsStreamDirectory($this->main_dir));

			$fm = new FormManager();
			$this->assertEquals('install_skipped', $fm->install(), 'install skipped with no parameters');

			$valid_params_mocked = $this->valid_params;
			$valid_params_mocked['installDir'] = vfsStream::url($this->main_dir);
			$fm = new FormManager($valid_params_mocked);

			vfsStreamWrapper::getRoot()->chmod(0000);
			$this->assertEquals('install_failed', $fm->install(), 'install needs permission to occur');

			vfsStreamWrapper::getRoot()->chmod(0700);
			$this->assertEquals('installed', $fm->install(), 'good params allow for installation');

			$this->assertEquals('installed', $fm->install(), 'install occurs only once');

			vfsStreamWrapper::getRoot()->getChild('fm')->removeChild('submissions');
			$this->assertEquals('bad_install', $fm->install(), 'missing directories trigger bad install');

		}

		/** @test
		 *	@covers FormManager\FormManager::validateApplicationMode
		 */

		public function test_application_modes(){

			$this->main_dir = 'form_manager_root';
			vfsStreamWrapper::register();
			vfsStreamWrapper::setRoot(new vfsStreamDirectory($this->main_dir));

			$valid_params_mocked = $this->valid_params;
			$valid_params_mocked['installDir'] = vfsStream::url($this->main_dir);
			$fm = new FormManager($valid_params_mocked);

			$this->assertTrue($fm->validateApplicationMode('i'), 'i is a valid mode');
			$this->assertTrue($fm->validateApplicationMode('o'), 'o is a valid mode');
			$this->assertFalse($fm->validateApplicationMode('io'), 'i and o are the only valid modes');
			$this->assertFalse($fm->validateApplicationMode(), 'i and o are the only valid modes');
			$this->assertFalse($fm->validateApplicationMode(function(){exit();}), 'i and o are the only valid modes');
			$this->assertFalse($fm->validateApplicationMode(false), 'i and o are the only valid modes');

		}

		/** @test
		 *	@covers FormManager\FormManager::validateHash
		 */

		public function test_has_validation(){
			$this->main_dir = 'form_manager_root';
			vfsStreamWrapper::register();
			vfsStreamWrapper::setRoot(new vfsStreamDirectory($this->main_dir));

			$valid_params_mocked = $this->valid_params;
			$valid_params_mocked['installDir'] = vfsStream::url($this->main_dir);
			$fm = new FormManager($valid_params_mocked);
			$this->assertTrue($fm->validateHash('1234', 'input'), 'valid hashes should resolve to an input value');
			
		}


	}

?>
