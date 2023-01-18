<?php

use SmartDirectory\Bootstrap\System\Route\Route;

class RouteResponseMethodTest extends WP_UnitTestCase {

	public WP_REST_Server $server;
	public WP_REST_Request $wp_rest_request;

	/**
	 * Sets up the fixture, for example, open a network connection.
	 *
	 * This method is called before each test.
	 *
	 * @return void
	 */
	public function setUp(): void {

		parent::setUp();

		global $wp_rest_server;

		$wp_rest_server        = new WP_REST_Server();
		$this->wp_rest_request = new WP_REST_Request();
		do_action( 'rest_api_init' );
	}


	public function test_parameters() {

		$reflection_method = $this->get_method();
		$parameters        = $reflection_method->getParameters();

		/**
		 * Test get response method parameters
		 */
		$this->assertEquals( $parameters[0]->getName(), 'callback' );
		$this->assertEquals( $parameters[1]->getType()->getName(), 'WP_REST_Request' );

		$this->expectExceptionMessage( 'Please bind callable method in this route' );

		$reflection_method->invoke( new Route(), 10, $this->wp_rest_request );
	}


	public function test_invalid_class_method() {

		$controller = new class() {};

		$reflection_method = $this->get_method();

		$this->expectExceptionMessage( 'Method test_method does not exist' );

		$reflection_method->invoke( new Route(), array( $controller, 'test_method' ), $this->wp_rest_request );
	}

	public function get_method() {
		/**
		* make method accessible
		*/
		$reflection_method = new ReflectionMethod( Route::class, 'get_response' );
		$reflection_method->setAccessible( true );
		return $reflection_method;
	}
}
