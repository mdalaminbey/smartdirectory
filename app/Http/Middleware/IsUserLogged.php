<?php

namespace SmartDirectory\App\Http\Middleware;

defined( 'ABSPATH' ) || exit;

use SmartDirectory\Bootstrap\System\Contracts\Middleware;
use WP_REST_Request;

class IsUserLogged implements Middleware {

	public function handle( WP_REST_Request $wp_rest_request ) {
		return is_user_logged_in();
	}
}
