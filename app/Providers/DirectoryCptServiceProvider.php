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
            'singular_name'         => __( 'Directory listings', 'smartdirectory' ),
            'attributes'            => __( 'Item Attributes', 'smartdirectory' ),
            'all_items'             => __( 'All listings', 'smartdirectory' ),
            'add_new_item'          => __( 'Add New Item', 'smartdirectory' ),
            'add_new'               => __( 'Add New', 'smartdirectory' ),
            'new_item'              => __( 'New Item', 'smartdirectory' ),
            'edit_item'             => __( 'Edit Item', 'smartdirectory' ),
            'update_item'           => __( 'Update Item', 'smartdirectory' ),
            'view_item'             => __( 'View Item', 'smartdirectory' ),
            'view_items'            => __( 'View Items', 'smartdirectory' ),
            'search_items'          => __( 'Search Item', 'smartdirectory' ),
            'not_found'             => __( 'Not found', 'smartdirectory' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'smartdirectory' ),
            'featured_image'        => __( 'Featured Image', 'smartdirectory' ),
            'set_featured_image'    => __( 'Set featured image', 'smartdirectory' ),
            'remove_featured_image' => __( 'Remove featured image', 'smartdirectory' ),
            'use_featured_image'    => __( 'Use as featured image', 'smartdirectory' ),
            'insert_into_item'      => __( 'Insert into item', 'smartdirectory' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'smartdirectory' ),
            'items_list'            => __( 'Items list', 'smartdirectory' ),
            'items_list_navigation' => __( 'Items list navigation', 'smartdirectory' ),
            'filter_items_list'     => __( 'Filter items list', 'smartdirectory' )
        ];

        return [
            'label'               => __( 'Directory listings', true ),
            'description'         => __( 'Directory listings', 'smartdirectory' ),
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
