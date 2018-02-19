<?php

	declare(strict_types=1);
	
	use PHPUnit\Framework\TestCase;
	use FormManager\Validator\Submission;

	final class SubmissionTest extends TestCase
	{

		/** @test
		 *	@covers FormManager\Validator\Submission::keys
		 */

		public function test_for_form_submissions(){
			$postArgs = array(
				'postArg1' => 'Test1',
				'postArg2' => 'Test2'
			);
			$schema = array(
				array(
					'type' => 'group',
					'children' => array(
						array(
							'key' => 'postArg1',
							'features' => array(
								'validation' => array(
									'required' => true
								)
							)
						)
					)
				),
				array(
					'type' => 'abc',
					'key' => 'postArg2',
					'features' => array(
						'validation' => array(
							'required' => true
						)
					)
				)
			);
			$submission = new Submission();
			$this->assertFalse($submission->validSubmission, 'By default submissions are invalid');
			$this->assertInstanceOf(Submission::class, $submission, 'Submission should be an instance of itself');
			$submission->keys($postArgs, $schema);
			$this->assertTrue($submission->validSubmission, 'required arguments prove valid');
		}
	}