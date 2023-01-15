<?php
/**
 * Directory controller class handle admin all directory api
 *
 * @package  SmartDirectory
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GNU Public License
 * @since    1.0.0
 */

namespace SmartDirectory\App\Http\Controllers\Admin;

defined( 'ABSPATH' ) || exit;

use SmartDirectory\App\Library\RequestValidator;
use WP_REST_Request;

class DirectoryController {

	/**
	 * Directory create method
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
				'author'        => 'required|min:1|max:5',
				'preview_image' => 'required|min:1|max:5',
			)
		);

		if ( $validation->fails() ) {
			return smart_directory_response( array( 'messages' => $validation->errors() ), 500 );
		} else {

			$title            = sanitize_text_field( $wp_rest_request->get_param( 'title' ) );
			$content          = sanitize_text_field( $wp_rest_request->get_param( 'content' ) );
			$map_link         = esc_url_raw( $wp_rest_request->get_param( 'map_link' ) );
			$author_id        = intval( $wp_rest_request->get_param( 'author' ) );
			$preview_image_id = intval( $wp_rest_request->get_param( 'preview_image' ) );

			global $wpdb;

			//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->query(
				$wpdb->prepare(
					"INSERT INTO {$wpdb->prefix}smart_directories (title, content, map_link, author_id, preview_image_id, submission_date, status )
            		VALUES (%s, %s, %s, %s, %d, CURRENT_TIMESTAMP, 'approved')",
					$title,
					$content,
					$map_link,
					$author_id,
					$preview_image_id
				)
			);

			return smart_directory_response( array(), 201 );
		}
	}

	/**
	 * Directory update method
	 *
	 * @param WP_REST_Request $wp_rest_request Core class used to implement a REST request object.
	 * @return array
	 */
	public function update( WP_REST_Request $wp_rest_request ) {

		$validator = new RequestValidator();

		$validation = $validator->make(
			$wp_rest_request,
			array(
				'ID'            => 'required|min:1|max:5',
				'title'         => 'required|min:10|max:50',
				'content'       => 'required|min:10|max:150',
				'map_link'      => 'required|min:10|max:1000',
				'author'        => 'required|min:1|max:5',
				'preview_image' => 'required|min:1|max:5',
			)
		);

		if ( $validation->fails() ) {
			return smart_directory_response( array( 'messages' => $validation->errors() ), 500 );
		} else {

			$title            = sanitize_text_field( $wp_rest_request->get_param( 'title' ) );
			$content          = sanitize_text_field( $wp_rest_request->get_param( 'content' ) );
			$map_link         = esc_url_raw( $wp_rest_request->get_param( 'map_link' ) );
			$author_id        = intval( $wp_rest_request->get_param( 'author' ) );
			$preview_image_id = intval( $wp_rest_request->get_param( 'preview_image' ) );
			$id               = intval( $wp_rest_request->get_param( 'ID' ) );

			global $wpdb;

			//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->query(
				$wpdb->prepare(
					"UPDATE {$wpdb->prefix}smart_directories SET title = %s, content = %s, map_link = %s, author_id = %d, preview_image_id = %d WHERE ID = %d",
					$title,
					$content,
					$map_link,
					$author_id,
					$preview_image_id,
					$id
				)
			);

			return smart_directory_response( array(), 201 );
		}
	}
}
