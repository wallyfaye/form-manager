<?php

	declare(strict_types=1);
	
	use PHPUnit\Framework\TestCase;
	use FormManager\Validator\Application;

	final class ApplicationTest extends TestCase
	{

		/** @test
		 *	@covers FormManager\Validator\Application::mode
		 */

		public function test_for_application_modes(){

			$this->assertTrue(Application::mode('i'), 'i is a valid mode');
			$this->assertTrue(Application::mode('o'), 'o is a valid mode');
			$this->assertFalse(Application::mode('io'), 'i and o are the only valid modes');
			$this->assertFalse(Application::mode(), 'i and o are the only valid modes');
			$this->assertFalse(Application::mode(function(){exit();}), 'i and o are the only valid modes');
			$this->assertFalse(Application::mode(false), 'i and o are the only valid modes');

		}
	}