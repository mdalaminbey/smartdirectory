<?php

namespace SmartDirectory\App\Providers;

defined( 'ABSPATH' ) || exit;

use SmartDirectory\Bootstrap\System\Contracts\ServiceProvider;

class DirectoryCptServiceProvider extends ServiceProvider
{
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
        register_post_type( smart_directory_post_type(), $this->directory_cpt_arguments() );
    }

    private function directory_cpt_arguments()
    {
        $labels = [
            'singular_name'         => __( 'Directory listings', 'superdirectory' ),
            'attributes'            => __( 'Item Attributes', 'superdirectory' ),
            'all_items'             => __( 'All listings', 'superdirectory' ),
            'add_new_item'          => __( 'Add New Item', 'superdirectory' ),
            'add_new'               => __( 'Add New', 'superdirectory' ),
            'new_item'              => __( 'New Item', 'superdirectory' ),
            'edit_item'             => __( 'Edit Item', 'superdirectory' ),
            'update_item'           => __( 'Update Item', 'superdirectory' ),
            'view_item'             => __( 'View Item', 'superdirectory' ),
            'view_items'            => __( 'View Items', 'superdirectory' ),
            'search_items'          => __( 'Search Item', 'superdirectory' ),
            'not_found'             => __( 'Not found', 'superdirectory' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'superdirectory' ),
            'featured_image'        => __( 'Featured Image', 'superdirectory' ),
            'set_featured_image'    => __( 'Set featured image', 'superdirectory' ),
            'remove_featured_image' => __( 'Remove featured image', 'superdirectory' ),
            'use_featured_image'    => __( 'Use as featured image', 'superdirectory' ),
            'insert_into_item'      => __( 'Insert into item', 'superdirectory' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'superdirectory' ),
            'items_list'            => __( 'Items list', 'superdirectory' ),
            'items_list_navigation' => __( 'Items list navigation', 'superdirectory' ),
            'filter_items_list'     => __( 'Filter items list', 'superdirectory' )
        ];

        return [
            'label'               => __( 'Directory listings', true ),
            'description'         => __( 'Directory listings', 'superdirectory' ),
            'labels'              => $labels,
            'supports'            => ['title', 'editor', 'author'],
            'public'              => true,
            'hierarchical'        => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => false,
            'publicly_queryable'  => false,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-list-view'
        ];
    }
}
