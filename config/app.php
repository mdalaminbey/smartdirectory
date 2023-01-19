<?php
/**
 * Registered plugin core configuration
 *
 * @package  SmartDirectory
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GNU Public License
 * @since    1.0.0
 */

defined( 'ABSPATH' ) || exit;

use SmartDirectory\App\Http\Middleware\IsAdmin;
use SmartDirectory\App\Http\Middleware\IsUserLogged;
use SmartDirectory\App\Providers\Admin\MenuServiceProvider;
use SmartDirectory\App\Providers\BlockRegisterServiceProvider;
use SmartDirectory\App\Providers\LocalizationServiceProvider;
use SmartDirectory\App\Providers\ShortcodeServiceProvider;

return array(
	/**
	 * Plugin Current Version
	 */
	'version'         => '1.0.0',

	/**
	 * Service providers
	 */
	'providers'       => array(
		LocalizationServiceProvider::class,
		ShortcodeServiceProvider::class,
		BlockRegisterServiceProvider::class,
	),

	'admin_providers' => array(
		MenuServiceProvider::class,
	),
	/**
	 * Plugin Api Namespace
	 */
	'namespace'       => 'smart-directory',

	'api_versions'    => array( 'v1' ),

	'middleware'      => array(
		'is_user_logged' => IsUserLogged::class,
		'is_admin'       => IsAdmin::class,
	),
);
