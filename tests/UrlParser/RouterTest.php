<?php
	declare(strict_types=1);
	
	use PHPUnit\Framework\TestCase;
	use FormManager\UrlParser\Router;

	final class RouterTest extends TestCase
	{

		/** @test
		 *	@covers FormManager\UrlParser\Router::getUrlParams
		 */

		public function read_url_parameters(){
			$router = new Router();
			$this->assertInstanceOf(Router::class, $router, 'Router should be an instance of itself');

			$router->getApplicationMode();
		}
	}