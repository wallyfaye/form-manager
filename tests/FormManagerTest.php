<?php  

	declare(strict_types=1);
	
	use PHPUnit\Framework\TestCase;
	use FormManager\FormManager;

	final class FormManagerTest extends TestCase
	{

		/** @test
		 *	@covers FormManager\FormManager
		 */

		public function instantiate_form_manager(){
			$fm = new FormManager();
			$this->assertInstanceOf(FormManager::class, $fm, 'FormManager should be an instance of itself');
		}

		/** @test
		 *	@covers FormManager\FormManager::validateParams
		 */

		public function validate_parameters($value='')
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


	}

?>
