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
			$good_fm = new FormManager(array(
				'installDir' => __DIR__
			));
			$this->assertInstanceOf(FormManager::class, $good_fm, 'FormManager should be an instance of itself');
			$this->assertTrue($good_fm->params_valid);

			$bad_fm = new FormManager();
			$this->assertFalse($bad_fm->params_valid);

		}

		/** @test
		 *	@covers FormManager\FormManager::validate_params
		 */

		public function validate_parameters($value='')
		{

			// set test class and method
				$test_class = '\FormManager\FormManager';
				$test_method = 'validate_params';

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

			// conduct tests
				$this->assertTrue($method->invoke($fm, array('installDir' => __DIR__ )));
				$this->assertFalse($method->invoke($fm, array('installDir' => '' )));

		}


	}

?>
