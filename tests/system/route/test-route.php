<?php

use SmartDirectory\App\Http\Controllers\Admin\DirectoryController;
use SmartDirectory\Bootstrap\Application;
use SmartDirectory\Bootstrap\System\Route\RegisterRoute;
use SmartDirectory\Bootstrap\System\Route\Route;

class RouteTest extends WP_UnitTestCase {

	public WP_REST_Server $server;
	public $namespace;

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
		$wp_rest_server = new WP_REST_Server();
		do_action( 'rest_api_init' );

		/**
		 * Create RegisterRoute instance
		 *
		 * @var RegisterRoute $register_route
		 */
		$register_route = Application::$container->singleton( RegisterRoute::class );
		$version        = $register_route->get_version();

		$namespace = '/smart-directory';

		if ( ! empty( $version ) ) {
			$namespace .= '/' . $version;
		}

		$this->namespace = $namespace;
	}

	public function test_required_param_endpoint() {

		Route::get( 'get-required-api/{id}', function() {} );

		$full_api = $this->namespace . '/get-required-api/{id}';
		$full_api = $this->required_param( $full_api, array( array( '{id}' ), array( 'id' ) ) );

		$routes = rest_get_server()->get_routes( 'smart-directory' );

		$this->assertArrayHasKey( $full_api, $routes );
	}

	public function test_optional_param_endpoint() {

		Route::get( 'get-optional-api/{id}/{name?}', function() {} );

		$full_api = $this->namespace . '/get-optional-api/{id}/{name?}';
		$full_api = $this->optional_param( $full_api, array( array( '{id}', '{name?}' ), array( 'id', 'name' ) ) );

		$routes = rest_get_server()->get_routes( 'smart-directory' );

		$this->assertArrayHasKey( $full_api, $routes );
	}

	public function test_response() {

		$controller = new class() {
			public function index(): array {
				return array(
					'data'   => array( 'message' => 'Hello PHPUnit' ),
					'status' => 200,
				);
			}
		};

		Route::get( 'test-dependencies', array( $controller, 'index' ) );

		$full_api = $this->namespace . '/test-dependencies';

		$routes = rest_get_server()->get_routes( 'smart-directory' );

		$wp_rest_request = new WP_REST_Request();
		$wp_rest_request->set_param( 'return', true );

		$response = $routes[ $full_api ][0]['callback']( $wp_rest_request );

		$this->assertEquals(
			$response,
			array(
				'data'   => array( 'message' => 'Hello PHPUnit' ),
				'status' => 200,
			)
		);
	}

	/**
	 * Optional param regex format
	 *
	 * @param string $route
	 * @param array  $params
	 * @return string
	 */
	protected static function optional_param( string $route, array $params ): string {

		foreach ( $params[0] as $key => $value ) {
			$route = str_replace( '/' . $value, '(?:/(?P<' . str_replace( '?', '', $params[1][ $key ] ) . '>[-\w]+))?', $route );
		}

		return $route;
	}

	/**
	 * Required param regex format
	 *
	 * @param string $route
	 * @param array  $params
	 * @return string
	 */
	protected static function required_param( string $route, array $params ): string {

		foreach ( $params[0] as $key => $value ) {
			$route = str_replace( $value, '(?P<' . $params[1][ $key ] . '>[-\w]+)', $route );
		}

		return $route;
	}
}
