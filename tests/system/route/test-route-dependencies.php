<?php

use SmartDirectory\Bootstrap\System\Route\Route;

class RouteDependenciesTest extends WP_UnitTestCase {

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
		$this->assertEquals( $parameters[0]->getName(), 'parameters' );
		$this->assertEquals( $parameters[1]->getType()->getName(), 'WP_REST_Request' );
	}

	public function test_missing_type_hint() {

		$controller = new class() {
			public function index( $id ) {}
		};

		$reflection_method = $this->get_method();

		$controller_method = new ReflectionMethod( $controller, 'index' );
		$parameters        = $controller_method->getParameters();

		$this->expectErrorMessage( 'Failed to resolve because param "id" is missing a type hint' );

		$reflection_method->invoke( new Route(), $parameters, $this->wp_rest_request );
	}

	public function test_with_type_hint() {

		$controller = new class() {
			public function index( int $id, string $slug ) {}
			public function show( WP_REST_Request $wp_rest_request ) {}
		};

		$reflection_method = $this->get_method();

		/**
		 * Test build in type dependencies
		 */
		$controller_method = new ReflectionMethod( $controller, 'index' );
		$parameters        = $controller_method->getParameters();

		$this->wp_rest_request->set_param( 'slug', 'hello-php-unit' );
		$this->wp_rest_request->set_param( 'id', 10 );

		$response = $reflection_method->invoke( new Route(), $parameters, $this->wp_rest_request );

		$this->assertEquals( $response, array( 10, 'hello-php-unit' ) );

		/**
		 * Test WP_REST_Request type dependencies
		 */
		$controller_method = new ReflectionMethod( $controller, 'show' );
		$parameters        = $controller_method->getParameters();
		$response          = $reflection_method->invoke( new Route(), $parameters, $this->wp_rest_request );

		$this->assertEquals(
			$response[0]->get_params(),
			array(
				'id'   => 10,
				'slug' => 'hello-php-unit',
			)
		);
	}

	public function get_method() {
		/**
		* make method accessible
		*/
		$reflection_method = new ReflectionMethod( Route::class, 'get_dependencies' );
		$reflection_method->setAccessible( true );
		return $reflection_method;
	}
}
