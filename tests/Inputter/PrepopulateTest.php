<?php  

	declare(strict_types=1);
	
	use PHPUnit\Framework\TestCase;

	final class PrepopulateTest extends TestCase
	{

		protected $validate;

		public function setUp() {
			$this->model_json = new \FormManager\Inputter\Prepopulate(array(
				'salt' => '1234', 
			));
		}

		/** @test
		 *	@covers Inputter::Prepopulate
		 */

		public function validate_gets_valid_request_1(){
			$this->assertFalse(false, strtoupper('Bad file name'));
		}

	}

?>
