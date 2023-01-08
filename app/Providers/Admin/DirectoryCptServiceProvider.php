<?php

namespace SmartDirectory\App\Providers\Admin;

defined( 'ABSPATH' ) || exit;

use SmartDirectory\Bootstrap\System\Contracts\ServiceProvider;
use SmartDirectory\Bootstrap\System\View\View;
use WP_Post;

class DirectoryCptServiceProvider extends ServiceProvider
{
    public $post;

    public function boot()
    {
        add_action( 'init', [$this, 'action_init'] );
    }

    /**
     * Fires after WordPress has finished loading but before any headers are sent.
     *
     */
    public function action_init(): void
    {
        add_action( 'save_post_' . smart_directory_post_type(), [$this, 'action_save_post'], 10, 3 );
        add_action( 'add_meta_boxes', [$this, 'action_add_meta_boxes'], 10, 2 );
        add_action( 'before_delete_post', [$this, 'action_before_delete_post'], 10, 2 );
    }

    /**
     * Fires once a post has been saved.
     *
     * @param int      $post_ID Post ID.
     * @param \WP_Post $post    Post object.
     * @param bool     $update  Whether this is an existing post being updated.
     */
    public function action_save_post( int $post_ID, WP_Post $post, bool $update ): void
    {
        //phpcs:ignore WordPress.Security.NonceVerification.Missing -- This method can access only admin
        if ( !empty( $_POST['map_link'] ) ) {
            //phpcs:ignore
            update_post_meta( $post_ID, 'map_link', sanitize_url( wp_unslash( $_POST['map_link'] ) ) );
        }

        //phpcs: WordPress.Security.NonceVerification.Missing
        if ( !empty( $_FILES['preview_image']['tmp_name'] ) ) {

            if ( !function_exists( 'media_handle_upload' ) ) {

                require_once ABSPATH . 'wp-admin/includes/image.php';
                require_once ABSPATH . 'wp-admin/includes/file.php';
                require_once ABSPATH . 'wp-admin/includes/media.php';
            }

            /**
             * Remove old attachment
             */
            $old_image_id = get_post_meta( $post_ID, 'preview_image', true );
            wp_delete_attachment( $old_image_id, true );

            $preview_image_id = media_handle_upload( 'preview_image', $post_ID );

            if ( !is_wp_error( $preview_image_id ) ) {
                update_post_meta( $post_ID, 'preview_image', $preview_image_id );
            }
        }
    }

    /**
     * Fires before a post is deleted, at the start of wp_delete_post().
     *
     * @param int      $post_ID Post ID.
     * @param \WP_Post $post   Post object.
     */
    public function action_before_delete_post( int $post_ID, WP_Post $post ): void
    {
        $preview_image_id = get_post_meta( $post_ID, 'preview_image', true );
        wp_delete_attachment( $preview_image_id, true );
    }

    /**
     * Fires after all built-in meta boxes have been added.
     *
     * @param string   $post_type Post type.
     * @param \WP_Post $post      Post object.
     */
    public function action_add_meta_boxes( string $post_type, WP_Post $post ): void
    {
        if ( smart_directory_post_type() === $post_type ) {
            $this->post = $post;
            add_meta_box( 'directory_info', esc_html__( 'Directory Info', 'superdirectory' ), [$this, 'metabox_content'] );
        }
    }

    public function metabox_content()
    {
        View::render( 'admin/metabox', ['post_id' => $this->post->ID] );
    }
}
