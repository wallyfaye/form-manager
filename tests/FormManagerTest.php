<?php  

	declare(strict_types=1);
	
	use PHPUnit\Framework\TestCase;
	use FormManager\FormManager;

	final class FormManagerTest extends TestCase
	{

		/** @test
		 *	@covers FormManager
		 */

		public function instantiate_form_manager(){
			$this->assertInstanceOf(FormManager::class, new FormManager, 'FormManager should be an instance of itself');
		}

	}

?>
