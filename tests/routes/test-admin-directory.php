<?php

class AdminDirectoryApiTest extends WP_UnitTestCase {

	public WP_REST_Server $server;

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
		/**
		 * Register rest api
		 */
		$wp_rest_server = new WP_REST_Server();

		$this->server = $wp_rest_server;

		do_action( 'rest_api_init' );
	}

	/**
	 * Test Directory Create API
	 *
	 * @return void
	 */
	public function test_create() {

		$request = new WP_REST_Request( 'POST', '/smart-directory/v1/admin/directories' );
		$request->set_param( 'return', true );

		/**
		 * Is User Logged In Test
		 *
		 * @var WP_REST_Response $response
		 */
		$response = $this->server->dispatch( $request );
		$body     = $response->get_data();

		$this->assertEquals( 401, $body['data']['status'] );

		/**
		 * Do Login Action
		 */
		wp_set_current_user( 1 );

		/**
		 * Validation Test
		 */
		$response = $this->server->dispatch( $request );
		$body     = $response->get_data();
		$this->assertEquals( 500, $body['status'] );

		/**
		 * Success Test
		 */
		$request->set_param( 'title', 'Hello World' );
		$request->set_param( 'content', 'Test Content' );
		$request->set_param( 'map_link', 'https://goo.gl/maps/Bn7Qe8VidrYBoowL7' );
		$request->set_param( 'author', 1 );
		$request->set_param( 'preview_image', 1 );

		$response = $this->server->dispatch( $request );
		$body     = $response->get_data();

		$this->assertEquals( 201, $body['status'] );
	}

	/**
	 * Test Directory Update API
	 *
	 * @return void
	 */
	public function test_update() {

		$request = new WP_REST_Request( 'PATCH', '/smart-directory/v1/admin/directories' );
		$request->set_param( 'return', true );

		/**
		 * Is User Logged In Test
		 *
		 * @var WP_REST_Response $response
		 */
		$response = $this->server->dispatch( $request );
		$body     = $response->get_data();

		$this->assertEquals( 401, $body['data']['status'] );

		/**
		 * Do Login Action
		 */
		wp_set_current_user( 1 );

		/**
		 * Validation Test
		 */
		$response = $this->server->dispatch( $request );
		$body     = $response->get_data();
		$this->assertEquals( 500, $body['status'] );

		/**
		 * Success Test
		 */
		$request->set_param( 'ID', 20 );
		$request->set_param( 'title', 'Hello World' );
		$request->set_param( 'content', 'Test Content' );
		$request->set_param( 'map_link', 'https://goo.gl/maps/Bn7Qe8VidrYBoowL7' );
		$request->set_param( 'author', 1 );
		$request->set_param( 'preview_image', 1 );

		$response = $this->server->dispatch( $request );
		$body     = $response->get_data();

		$this->assertEquals( 201, $body['status'] );
	}
}
