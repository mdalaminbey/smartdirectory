<?php
/**
 * Register frontend related all scripts and styles
 *
 * @package  SmartDirectory
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GNU Public License
 * @since    1.0.0
 */

use SmartDirectory\Bootstrap\System\Utils\Common;

defined( 'ABSPATH' ) || exit;

wp_enqueue_script( 'smart-directory-pagination-js', Common::asset( 'js/simplePagination.js' ), array( 'jquery' ), Common::version() );
wp_enqueue_script( 'smart-directory-js', Common::asset( 'js/app.js' ), array( 'jquery' ), Common::version() );
wp_enqueue_style( 'smart-directory-pagination-css', Common::asset( 'css/simplePagination.css' ), array(), Common::version() );
wp_enqueue_style( 'smart-directory-tailwind', Common::asset( 'css/app.css' ), array(), Common::version() );
