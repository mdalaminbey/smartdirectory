<?php

class DirectoryApiTest extends WP_UnitTestCase {

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
	 * Public directories api test
	 *
	 * @return void
	 */
	public function test_directories() {

		$request = new WP_REST_Request( 'GET', '/smart-directory/v1/directories' );
		$request->set_param( 'return', true );

		$response = $this->server->dispatch( $request );
		$body     = $response->get_data();

		$this->assertEquals( 200, $body['status'] );
		$this->assertStringContainsString( 'Navigate', $body['data']['html'] );
	}
}
