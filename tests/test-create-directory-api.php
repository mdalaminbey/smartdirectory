<?php

use SmartDirectory\Bootstrap\Application;

class CreateDirectoryApiTest extends WP_UnitTestCase
{
    public $server;

    public function setUp(): void
    {
        parent::setUp();

        /**
         * Register rest api
         */
        global $wp_rest_server;
        $this->server = $wp_rest_server = new WP_REST_Server;
        do_action( 'rest_api_init' );
    }

    public function test_create()
    {
        $request = new WP_REST_Request( 'POST', '/smart-directory/v1/directory/create' );
        $request->set_param( 'return', true );

        /**
         * Is User Logged In Test
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
        file_put_contents( $temp_file, $content );

        $array = [
            'name'     => 'navigation.png',
            'type'     => mime_content_type( $file_path ),
            'tmp_name' => $temp_file,
            'error'    => 0,
            'size'     => filesize( $file_path )
        ];

        $_FILES['preview_image'] = $array;

        $request->set_file_params( ['preview_image' => $array] );

        $response = $this->server->dispatch( $request );
        $body     = $response->get_data();

        $this->assertEquals( 201, $body['status'] );
    }
}
