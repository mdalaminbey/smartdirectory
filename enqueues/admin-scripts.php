<?php
/**
 * Register admin related all scripts and styles
 *
 * @package  SmartDirectory
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GNU Public License
 * @since    1.0.0
 */

use SmartDirectory\Bootstrap\System\Utils\Common;

defined( 'ABSPATH' ) || exit;

global $current_screen;

/**
 * Load directory add new page script
 */
if ( isset( $current_screen->id ) && 'smart-directory_page_smart-directory-add-new' === $current_screen->id ) {
	wp_enqueue_media();
	wp_enqueue_script( 'smart-directory-js', Common::asset( 'js/admin/app.js' ), array( 'jquery' ), Common::version(), true );
}

wp_enqueue_style( 'smart-directory-tailwind', Common::asset( 'css/app.css' ), array(), Common::version() );
