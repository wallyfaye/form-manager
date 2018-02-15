<?php

	declare(strict_types=1);
	
	use PHPUnit\Framework\TestCase;
	use FormManager\Hasher\Hash;

	final class HashTest extends TestCase
	{

		/** @test
		 *	@covers FormManager\Hasher\Hash
		 */

		public function instantiate_hash_class(){

			$hash = new Hash();
			$this->assertInstanceOf(Hash::class, $hash, 'Hash should be an instance of itself');

		}

		/** @test
		 *	@covers FormManager\Hasher\Hash::generate
		 *	@covers FormManager\Hasher\Hash::input_hash
		 */

		public function geneterate_input_hash(){
			$hash = new Hash('input', 'fjlkfjlakjf');
			$this->assertEquals('string', gettype($hash->generate('test')), 'valid formats should return strings');
			$this->assertNotEquals('', $hash->generate('test'), 'valid formats should not return empty strings');

			$hash = new Hash('invalid_format', 'fjlkfjlakjf');
			$this->assertEquals('', $hash->generate('test'), 'invalid formats should return empty strings');

		}

	}