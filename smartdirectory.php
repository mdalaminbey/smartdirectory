<?php
/**
 * Plugin Name:       SmartDirectory
 * Plugin URI:        http://wordpress.org/plugins/smartdirectory/
 * Description:       Test project for the Senior WordPress Plugin Developer position at SovWare
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Tested up to:      6.1.1
 * Author:            MdAlAmin
 * Author URI:        http://mdalaminbey.com
 * License:           GPL v3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       smartdirectory
 * Domain Path:       /languages
 */

defined( 'ABSPATH' ) || exit;

use SmartDirectory\Bootstrap\Application;
use SmartDirectory\Bootstrap\System\Activation;

require_once __DIR__ . '/vendor/autoload.php';

class SmartDirectory {

	/**
	 * Boot plugin
	 *
	 * @return void
	 */
	public static function boot() {
		$app = Application::instance();

		register_activation_hook( __FILE__, array( new Activation(), 'execute' ) );
		/**
		 * Fires once activated plugins have loaded.
		 */
		add_action(
			'plugins_loaded',
			function () use ( $app ): void {
				$app->boot( __DIR__, __FILE__ );
			}
		);
	}
}

SmartDirectory::boot();
