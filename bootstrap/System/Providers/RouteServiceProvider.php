<?php

namespace SmartDirectory\Bootstrap\System\Providers;

use SmartDirectory\Bootstrap\System\Contracts\ServiceProvider;
use SmartDirectory\Bootstrap\System\Route\RegisterRoute;
use WP_REST_Server;

final class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        add_action( 'rest_api_init', [$this, 'action_rest_api_init'] );
    }

    /**
     * Fires when preparing to serve a REST API request.
     */
    public function action_rest_api_init(): void
    {
        $application = $this->application::$instance;

        $config = $application::$config;

        /**
         * Create RegisterRoute instance
         * @var RegisterRoute $registerRoute
         */
        $registerRoute = $application->make( RegisterRoute::class );

        $registerRoute->set_namespace( $config['namespace'] );

        include_once $application->get_root_dir() . '/routes/api.php';

        foreach ( $config['api_versions'] as $version ) {
            $registerRoute->set_version( $version );
            include_once $application->get_root_dir() . '/routes/' . $version . '.php';
        }
    }
}
