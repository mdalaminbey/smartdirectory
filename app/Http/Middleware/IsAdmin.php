<?php
/**
 * The IsAdmin middleware class checks whether the current user is logged in. This class is registered in app/config.php inside the middleware array
 *
 * @package  SmartDirectory
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GNU Public License
 * @since    1.0.0
 */

namespace SmartDirectory\App\Http\Middleware;

defined( 'ABSPATH' ) || exit;

use SmartDirectory\Bootstrap\System\Contracts\Middleware;
use WP_REST_Request;

class IsAdmin implements Middleware {

	/**
	 * Handle method checks whether the user is logged in or not.
	 *
	 * @param WP_REST_Request $wp_rest_request Core class used to implement a REST request object.
	 * @return bool
	 */
	public function handle( WP_REST_Request $wp_rest_request ) {
		return current_user_can( 'manage_options' );
	}
}
