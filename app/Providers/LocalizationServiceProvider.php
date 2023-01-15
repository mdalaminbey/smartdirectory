<?php
/**
 * The localization service provider class is load all global localization
 *
 * @package  SmartDirectory
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GNU Public License
 * @since    1.0.0
 */

namespace SmartDirectory\App\Providers;

defined( 'ABSPATH' ) || exit;

use SmartDirectory\Bootstrap\System\Contracts\ServiceProvider;

class LocalizationServiceProvider extends ServiceProvider {

	/**
	 * The boot method is called immediately after the service provider calls the constructor
	 *
	 * @return void
	 */
	public function boot() {
		add_action( 'init', array( $this, 'action_init' ) );
	}

	/**
	 * Fires after WordPress has finished loading but before any headers are sent.
	 */
	public function action_init(): void {
		add_action( 'wp_head', array( $this, 'action_wp_head' ) );
		add_action( 'admin_head', array( $this, 'action_wp_head' ) );
	}

	/**
	 * Prints scripts or data in the head tag on the front end.
	 */
	public function action_wp_head(): void {
		$args = array(
			'root'  => esc_url_raw( rest_url() ),
			'nonce' => wp_create_nonce( 'wp_rest' ),
		);
		?>
		<script>
			var SmartDirectorySettings = <?php echo wp_json_encode( $args ); ?>
		</script>
		<?php
	}
}
