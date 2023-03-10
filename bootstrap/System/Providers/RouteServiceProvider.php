<?php

namespace SmartDirectory\Bootstrap\System\Providers;

defined( 'ABSPATH' ) || exit;

use SmartDirectory\Bootstrap\System\Contracts\ServiceProvider;
use SmartDirectory\Bootstrap\System\Route\RegisterRoute;

final class RouteServiceProvider extends ServiceProvider {

	public function boot() {
		add_action( 'rest_api_init', array( $this, 'action_rest_api_init' ) );
	}

	/**
	 * Fires when preparing to serve a REST API request.
	 */
	public function action_rest_api_init(): void {

		$application = $this->application;
		$config      = $application::$config;
		$container   = $application::$container;

		/**
		* Create RegisterRoute instance
		*
		* @var RegisterRoute $register_route
		*/
		$register_route = $container->singleton( RegisterRoute::class );

		$register_route->set_namespace( $config['namespace'] );

		include $application->get_root_dir() . '/routes/api.php';

		foreach ( $config['api_versions'] as $version ) {
			$register_route->set_version( $version );
			include $application->get_root_dir() . '/routes/' . $version . '.php';
		}
	}
}
