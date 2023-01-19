<?php
/**
 * When the plugin is activated, the class is executed
 *
 * @package  SmartDirectory
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GNU Public License
 * @since    1.0.0
 */

namespace SmartDirectory\Bootstrap\System;

defined( 'ABSPATH' ) || exit;

final class Activation {

	/**
	 * Create `smart_directories` db table
	 *
	 * @return array
	 */
	public function execute() {

		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}smart_directories (
			ID bigint UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
			title varchar(100) NOT NULL,
			content varchar(200) NOT NULL,
			status varchar(15) NOT NULL,
			map_link longtext NOT NULL, 
			preview_image_id bigint UNSIGNED NOT NULL,
			author_id bigint UNSIGNED NOT NULL,
			submission_date timestamp NOT NULL
		) {$charset_collate}";

		if ( ! function_exists( 'dbDelta' ) ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}

		return dbDelta( $sql );
	}
}
