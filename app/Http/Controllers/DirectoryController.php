<?php
/**
 * Directory controller class handle frontend all directory api
 *
 * @package  SmartDirectory
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GNU Public License
 * @since    1.0.0
 */

namespace SmartDirectory\App\Http\Controllers;

defined( 'ABSPATH' ) || exit;

use SmartDirectory\App\Library\RequestValidator;
use SmartDirectory\Bootstrap\System\View\View;
use WP_REST_Request;

class DirectoryController {

	/**
	 * Get director list
	 *
	 * @return array
	 */
	public function index() {
		ob_start();
		View::render( 'frontend/directories/list' );
		$html = ob_get_clean();
		return smart_directory_response( array( 'html' => $html ) );
	}

	/**
	 * Get specific user director list
	 *
	 * @return array
	 */
	public function user_directories() {
		ob_start();
		View::render( 'frontend/user-directories/list' );
		$html = ob_get_clean();
		return smart_directory_response( array( 'html' => $html ) );
	}

	/**
	 * Logged user directory create method
	 *
	 * @param WP_REST_Request $wp_rest_request Core class used to implement a REST request object.
	 * @return array
	 */
	public function create( WP_REST_Request $wp_rest_request ) {

		$validator = new RequestValidator();

		$validation = $validator->make(
			$wp_rest_request,
			array(
				'title'         => 'required|min:10|max:50',
				'content'       => 'required|min:10|max:150',
				'map_link'      => 'required|min:10|max:1000',
				'preview_image' => 'file:png,jpeg|size:500',
			)
		);

		if ( $validation->fails() ) {
			return smart_directory_response( array( 'messages' => $validation->errors() ), 500 );
		} else {
			$title    = sanitize_text_field( $wp_rest_request->get_param( 'title' ) );
			$content  = sanitize_text_field( $wp_rest_request->get_param( 'content' ) );
			$map_link = esc_url_raw( $wp_rest_request->get_param( 'map_link' ) );

			if ( ! function_exists( 'media_handle_upload' ) ) {
				require_once ABSPATH . 'wp-admin/includes/image.php';
				require_once ABSPATH . 'wp-admin/includes/file.php';
				require_once ABSPATH . 'wp-admin/includes/media.php';
			}

			$preview_image_id = media_handle_upload( 'preview_image', 0 );

			if ( is_wp_error( $preview_image_id ) ) {
				$preview_image_id = 0;
			}

			global $wpdb;

			$wpdb->query(
				$wpdb->prepare(
					"INSERT INTO {$wpdb->prefix}smart_directories (title, content, map_link, author_id, preview_image_id, submission_date, status)
                    VALUES (%s, %s, %s, %s, %d, CURRENT_TIMESTAMP, 'pending')",
					$title,
					$content,
					$map_link,
					get_current_user_id(),
					$preview_image_id
				)
			);

			return smart_directory_response( array(), 201 );
		}
	}
}
