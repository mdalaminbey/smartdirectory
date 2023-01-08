<?php

class DirectoryApiTest extends WP_UnitTestCase
{
    public $server;

    public function setUp(): void
    {
        parent::setUp();

        global $wp_rest_server;
        $this->server = $wp_rest_server = new WP_REST_Server;

        /**
         * Create Demo Directory
         */
        $this->factory()->post->create( [
            'post_type'   => smart_directory_post_type(),
            'post_title'  => 'Test Post',
            'post_status' => 'publish'
        ] );

        do_action( 'rest_api_init' );
    }

    public function test_directory()
    {
        $request = new WP_REST_Request( 'POST', '/smart-directory/v1/directory' );
        $request->set_param( 'return', true );

        $response = $this->server->dispatch( $request );
        $body     = $response->get_data();

        $this->assertEquals( 200, $body['status'] );
        $this->assertStringContainsString( 'Navigate', $body['data']['html'] );
    }

    public function test_user_directory()
    {
        /**
         * Create Demo Directory
         */
        $request = new WP_REST_Request( 'POST', '/smart-directory/v1/directory/user-directories' );
        $request->set_param( 'return', true );

        /**
         * Is User Logged In Test
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
}
