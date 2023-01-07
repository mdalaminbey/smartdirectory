<?php

namespace SmartDirectory\App\Http\Controllers;

defined( 'ABSPATH' ) || exit;

use SmartDirectory\App\Library\RequestValidator;
use WP_REST_Request;

class DirectoryController
{
    public function index()
    {
    }

    public function user_directories()
    {
    }

    public function create( WP_REST_Request $wp_rest_request )
    {
        $validator  = new RequestValidator;
        $validation = $validator->make( [
            'title'         => 'required|min:10|max:50',
            'content'       => 'required|min:10|max:150',
            'map_link'      => 'required|min:10|max:1000',
            'preview_image' => 'file:png,jpeg|size:500'
        ] );

        if ( $validation->fails() ) {
            wp_send_json( ['messages' => $validation->errors()], 500 );
        } else {
            $post_id = wp_insert_post( [
                'post_type'    => smart_directory_post_type(),
                'post_title'   => sanitize_text_field( $wp_rest_request->get_param( 'title' ) ),
                'post_content' => sanitize_text_field( $wp_rest_request->get_param( 'content' ) ),
                'post_status'  => 'pending',
                'post_author'  => get_current_user_id()
            ] );

            add_post_meta( $post_id, 'map_link', sanitize_url( $wp_rest_request->get_param( 'map_link' ) ) );

            if ( !function_exists( 'media_handle_upload' ) ) {

                require_once ABSPATH . 'wp-admin/includes/image.php';
                require_once ABSPATH . 'wp-admin/includes/file.php';
                require_once ABSPATH . 'wp-admin/includes/media.php';
            }

            $preview_image_id = media_handle_upload( 'preview_image', $post_id );

            if ( !is_wp_error( $preview_image_id ) ) {
                add_post_meta( $post_id, 'preview_image', $preview_image_id );
            }

            wp_send_json( [], 201 );
        }
    }
}
