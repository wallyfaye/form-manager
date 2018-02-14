<?php  

	declare(strict_types=1);
	
	use PHPUnit\Framework\TestCase;
	use FormManager\FormManager;
	use org\bovigo\vfs\vfsStream;
	use org\bovigo\vfs\vfsStreamWrapper;
	use org\bovigo\vfs\vfsStreamDirectory;

	final class FormManagerTest extends TestCase
	{

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

			// conduct tests
				$this->assertTrue($method->invoke($fm, array('installDir' => __DIR__ )));
				$this->assertFalse($method->invoke($fm, array('installDir' => '' )));

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

			$fm = new FormManager(array(
				'installDir' => vfsStream::url($this->main_dir)
			));

			vfsStreamWrapper::getRoot()->chmod(0000);
			$this->assertEquals('install_failed', $fm->install(), 'install needs permission to occur');

			vfsStreamWrapper::getRoot()->chmod(0700);
			$this->assertEquals('installed', $fm->install(), 'good params allow for installation');

			$this->assertEquals('installed', $fm->install(), 'install occurs only once');

			vfsStreamWrapper::getRoot()->getChild('fm')->removeChild('submissions');
			$this->assertEquals('bad_install', $fm->install(), 'missing directories trigger bad install');

		}

		/** @test
		 *	@covers FormManager\FormManager::validateRunMode
		 */

		public function test_application_modes(){

			$this->main_dir = 'form_manager_root';
			vfsStreamWrapper::register();
			vfsStreamWrapper::setRoot(new vfsStreamDirectory($this->main_dir));

			$fm = new FormManager(array(
				'installDir' => vfsStream::url($this->main_dir)
			));

			$this->assertTrue($fm->validateRunMode('i'), 'i is a valid mode');
			$this->assertTrue($fm->validateRunMode('o'), 'o is a valid mode');
			$this->assertFalse($fm->validateRunMode('io'), 'i and o are the only valid modes');
			$this->assertFalse($fm->validateRunMode(), 'i and o are the only valid modes');
			$this->assertFalse($fm->validateRunMode(function(){exit();}), 'i and o are the only valid modes');
			$this->assertFalse($fm->validateRunMode(false), 'i and o are the only valid modes');

		}


	}

?>
