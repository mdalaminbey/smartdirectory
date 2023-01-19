<?php
/**
 * SmartDocs core app
 *
 * @package  SmartDirectory
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GNU Public License
 * @since    1.0.0
 */

namespace SmartDirectory\Bootstrap;

defined( 'ABSPATH' ) || exit;

use SmartDirectory\Bootstrap\System\Configs\Config;
use SmartDirectory\Bootstrap\System\Contracts\ServiceProvider;
use SmartDirectory\Bootstrap\System\Di\Container;
use SmartDirectory\Bootstrap\System\Providers\EnqueueServiceProvider;
use SmartDirectory\Bootstrap\System\Providers\RouteServiceProvider;

final class Application extends Config {

	public static $instance;
	public static $config;
	protected static $is_boot = false;
	protected static $root_dir;
	protected static $root_url;
	public static Container $container;

	/**
	 * @return static
	 */
	public static function instance() {
		if ( ! static::$instance ) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * This method is helping to load the plugin
	 *
	 * @param string $root_dir plugin root file `__DIR__`.
	 * @param string $root_file plugin root file `__FILE__`.
	 * @return void
	 */
	public function boot( string $root_dir, string $root_file ) {

		if ( static::$is_boot ) {
			return;
		}

		static::$is_boot   = true;
		static::$container = new Container();

		$this->set_root_dir_and_url( $root_dir, $root_file );
		$this->set_config();
		$this->run_system_provider();
		$this->run_provider();
	}

	/**
	 * Set plugin core config from `config/app.php`
	 *
	 * @return void
	 */
	private function set_config() {
		static::$config = $this->get_config( 'app' );
	}

	/**
	 * Get config form `config/{$file_name}.php`
	 *
	 * @param string $file_name config file name.
	 * @return bool|array
	 */
	public function get_config( string $file_name ) {
		return $this->get_config_form_file( $file_name, $this->get_root_dir() );
	}

	/**
	 * Boot plugin system provider
	 *
	 * @return void
	 */
	private function run_system_provider() {

		foreach ( $this->get_system_provider() as $provider ) {
			/**
			 * @var ServiceProvider $provider_object
			 */
			$provider_object = static::$container->singleton( $provider );
			$provider_object->boot();
		}
	}

	/**
	 * Boot Admin and Other form `config/app.php`
	 *
	 * @return void
	 */
	public function run_provider() {

		if ( is_admin() ) {
			foreach ( static::$config['admin_providers'] as $provider ) {
				/**
				 * @var ServiceProvider $provider_object
				 */
				$provider_object = static::$container->singleton( $provider );
				$provider_object->boot( static::$instance );
			}
		}

		foreach ( static::$config['providers'] as $provider ) {
			/**
			 * @var ServiceProvider $provider_object
			 */
			$provider_object = static::$container->singleton( $provider );
			$provider_object->boot( static::$instance );
		}
	}

	/**
	 * Set plugin root directory and path
	 *
	 * @param string $root_dir plugin root file `__DIR__`.
	 * @param string $root_file plugin root file `__FILE__`.
	 * @return void
	 */
	private function set_root_dir_and_url( string $root_dir, string $root_file ) {
		static::$root_dir = $root_dir;
		static::$root_url = trailingslashit( plugin_dir_url( $root_file ) );
	}


	/**
	 * Get plugin root directory
	 *
	 * @return string
	 */
	public function get_root_dir(): string {
		return static::$root_dir;
	}

	/**
	 * Get plugin root url
	 *
	 * @return string
	 */
	public function get_root_url(): string {
		return static::$root_url;
	}

	/**
	 * Register system service provider
	 *
	 * @return array
	 */
	private function get_system_provider() {
		return array(
			RouteServiceProvider::class,
			EnqueueServiceProvider::class,
		);
	}
}
