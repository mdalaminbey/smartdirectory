<?php

namespace SmartDirectory\Bootstrap\System\Contracts;

defined( 'ABSPATH' ) || exit;

use WP_REST_Request;

interface Middleware {

	public function handle( WP_REST_Request $wp_rest_request );
}
