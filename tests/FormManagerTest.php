<?php  

	declare(strict_types=1);
	
	use PHPUnit\Framework\TestCase;
	use FormManager\FormManager;

	final class FormManagerTest extends TestCase
	{

		/** @test
		 *	@covers FormManager::validate_params
		 */

		public function validate_parameters($value='')
		{

			// set test class and method
				$test_class = '\FormManager\FormManager';
				$test_method = 'validate_params';
				$test_param_array = array('installDir' => __DIR__ );

			// create mock of class
				$fm = $this->getMockBuilder($test_class)
					->setMethods([$test_method])
					->disableOriginalConstructor()
					->getMock();

			// create reflection
				$reflectedClass = new ReflectionClass(get_class($fm));

			// make private method accessible
				$method = $reflectedClass->getMethod($test_method);
				$method->setAccessible(true);

			// conduct test
				$this->assertTrue($method->invoke($fm, $test_param_array));

		}


	}

?>
