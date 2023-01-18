<?php

use SmartDirectory\Bootstrap\Application;

class UserDirectoryTest extends WP_UnitTestCase {

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

		/**
		 * Create Demo Directory
		 */
		global $wpdb;
        //phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$wpdb->query(
			"INSERT INTO {$wpdb->prefix}smart_directories (title, content, map_link, author_id, preview_image_id, submission_date, status )
                VALUES ('Directory Title', 'Directory Content', 'https://goo.gl/maps/hFW4kqbFtjQGn5jo6', 1, 20, CURRENT_TIMESTAMP, 'approved')"
		);

		do_action( 'rest_api_init' );
	}

	/**
	 * Specific user directory get api test
	 *
	 * @return void
	 */
	public function test_user_directories() {
		/**
		 * Create Demo Directory
		 */
		$request = new WP_REST_Request( 'GET', '/smart-directory/v1/directories/user-directories' );
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
		 * Success Test
		 */
		wp_set_current_user( 1 );
		$response = $this->server->dispatch( $request );
		$body     = $response->get_data();
		$this->assertEquals( 200, $body['status'] );
	}

	/**
	 * Specific user directory create api test
	 *
	 * @return void
	 */
	public function test_create() {

		$request = new WP_REST_Request( 'POST', '/smart-directory/v1/directories' );
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
		$root_dir  = Application::$instance->get_root_dir();
		$file_path = $root_dir . '/assets/img/navigation.png';

		$request->set_param( 'title', 'Hello World' );
		$request->set_param( 'content', 'Test Content' );
		$request->set_param( 'map_link', 'https://goo.gl/maps/Bn7Qe8VidrYBoowL7' );

		/**
		 * Create temp file for preview
		 */
		$content   = file_get_contents( $file_path );
		$temp_file = tempnam( sys_get_temp_dir(), 'Tux' );

		global $wp_filesystem;
		WP_Filesystem();
		$wp_filesystem->put_contents( $file_path, $content );

		$array = array(
			'name'     => 'navigation.png',
			'type'     => mime_content_type( $file_path ),
			'tmp_name' => $temp_file,
			'error'    => 0,
			'size'     => filesize( $file_path ),
		);

		$_FILES['preview_image'] = $array;

		$request->set_file_params( array( 'preview_image' => $array ) );

		$response = $this->server->dispatch( $request );
		$body     = $response->get_data();

		$this->assertEquals( 201, $body['status'] );
	}
}
