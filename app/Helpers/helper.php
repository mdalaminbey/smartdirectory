<?php

defined( 'ABSPATH' ) || exit;

function smart_directory_response( $data, $status = 200 ) {
	return compact( 'data', 'status' );
}

function smart_directory_count_total() {
	global $wpdb;

	$counts = wp_cache_get( 'smart-directories-total' );

	if ( ! empty( $counts ) ) {
		return $counts;
	}

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
	$results = $wpdb->get_results( "SELECT status, COUNT( * ) AS num_posts FROM {$wpdb->prefix}smart_directories WHERE 1=1 GROUP BY status", ARRAY_A );

	$counts = array( 'all' => 0 );

	foreach ( $results as $row ) {
		$counts[ $row['status'] ] = $row['num_posts'];
		$counts['all']           += $row['num_posts'];
	}

	wp_cache_set( 'smart-directories-total', $counts );

	return $counts;
}

function smart_directory_kses_allowed() {
	return array(
		'a'          => array(
			'class'  => array(),
			'href'   => array(),
			'rel'    => array(),
			'title'  => array(),
			'target' => array(),
		),
		'abbr'       => array(
			'title' => array(),
		),
		'b'          => array(),
		'blockquote' => array(
			'cite' => array(),
		),
		'cite'       => array(
			'title' => array(),
		),
		'code'       => array(),
		'del'        => array(
			'datetime' => array(),
			'title'    => array(),
		),
		'dd'         => array(),
		'div'        => array(
			'class' => array(),
			'title' => array(),
			'style' => array(),
		),
		'dl'         => array(),
		'dt'         => array(),
		'em'         => array(),
		'h1'         => array(
			'class' => array(),
		),
		'h2'         => array(
			'class' => array(),
		),
		'h3'         => array(
			'class' => array(),
		),
		'h4'         => array(
			'class' => array(),
		),
		'h5'         => array(
			'class' => array(),
		),
		'h6'         => array(
			'class' => array(),
		),
		'i'          => array(
			'class' => array(),
		),
		'img'        => array(
			'alt'    => array(),
			'class'  => array(),
			'height' => array(),
			'src'    => array(),
			'width'  => array(),
		),
		'li'         => array(
			'class' => array(),
		),
		'ol'         => array(
			'class' => array(),
		),
		'p'          => array(
			'class' => array(),
		),
		'q'          => array(
			'cite'  => array(),
			'title' => array(),
		),
		'span'       => array(
			'class' => array(),
			'title' => array(),
			'style' => array(),
		),
		'iframe'     => array(
			'width'       => array(),
			'height'      => array(),
			'scrolling'   => array(),
			'frameborder' => array(),
			'allow'       => array(),
			'src'         => array(),
		),
		'strike'     => array(
			'class' => array(),
		),
		'br'         => array(
			'class' => array(),
		),
		'strong'     => array(
			'class' => array(),
		),
		'ul'         => array(
			'class' => array(),
		),
		'ins'        => array(
			'class'    => array(),
			'datetime' => array(),
		),
	);
}
