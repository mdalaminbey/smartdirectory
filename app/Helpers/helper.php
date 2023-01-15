<?php

defined( 'ABSPATH' ) || exit;

use SmartDirectory\Bootstrap\Application;

function smart_directory_post_type()
{
    return Application::$config['post_types']['directory'];
}

function smart_directory_response( $data, $status = 200 )
{
    return compact('data', 'status');
}

function smart_directory_count_total() {
    global $wpdb;

    $counts = wp_cache_get( 'smart-directories-total' );

    if ( ! empty( $counts ) ) {
        return $counts;
    }

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
    $results = $wpdb->get_results( "SELECT status, COUNT( * ) AS num_posts FROM {$wpdb->prefix}smart_directories WHERE 1=1 GROUP BY status", ARRAY_A );

    $counts = array('all' => 0);

    foreach ( $results as $row ) {
        $counts[$row['status']] = $row['num_posts'];
        $counts['all']         += $row['num_posts'];
    }

    wp_cache_set( 'smart-directories-total', $counts );
    
    return $counts;
}

function smart_directory_kses_allowed()
{
    return [
        'a'          => [
            'class'  => [],
            'href'   => [],
            'rel'    => [],
            'title'  => [],
            'target' => []
        ],
        'abbr'       => [
            'title' => []
        ],
        'b'          => [],
        'blockquote' => [
            'cite' => []
        ],
        'cite'       => [
            'title' => []
        ],
        'code'       => [],
        'del'        => [
            'datetime' => [],
            'title'    => []
        ],
        'dd'         => [],
        'div'        => [
            'class' => [],
            'title' => [],
            'style' => []
        ],
        'dl'         => [],
        'dt'         => [],
        'em'         => [],
        'h1'         => [
            'class' => []
        ],
        'h2'         => [
            'class' => []
        ],
        'h3'         => [
            'class' => []
        ],
        'h4'         => [
            'class' => []
        ],
        'h5'         => [
            'class' => []
        ],
        'h6'         => [
            'class' => []
        ],
        'i'          => [
            'class' => []
        ],
        'img'        => [
            'alt'    => [],
            'class'  => [],
            'height' => [],
            'src'    => [],
            'width'  => []
        ],
        'li'         => [
            'class' => []
        ],
        'ol'         => [
            'class' => []
        ],
        'p'          => [
            'class' => []
        ],
        'q'          => [
            'cite'  => [],
            'title' => []
        ],
        'span'       => [
            'class' => [],
            'title' => [],
            'style' => []
        ],
        'iframe'     => [
            'width'       => [],
            'height'      => [],
            'scrolling'   => [],
            'frameborder' => [],
            'allow'       => [],
            'src'         => []
        ],
        'strike'     => [
            'class' => []
        ],
        'br'         => [
            'class' => []
        ],
        'strong'     => [
            'class' => []
        ],
        'ul'         => [
            'class' => []
        ],
        'ins'        => [
            'class'    => [],
            'datetime' => []
        ]
    ];
}
