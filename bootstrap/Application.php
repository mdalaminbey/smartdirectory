<?php

namespace SmartDirectory\Bootstrap;

use SmartDirectory\Bootstrap\System\Configs\Config;
use SmartDirectory\Bootstrap\System\Providers\EnqueueServiceProvider;
use SmartDirectory\Bootstrap\System\Providers\RouteServiceProvider;

final class Application extends Config
{
    public static $instance, $config;
    protected static $instances = [], $is_boot = false, $root_dir, $root_url;

    /**
     * @return static
     */
    public static function instance()
    {
        if ( !static::$instance ) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    public function boot( string $root_dir, string $root_file )
    {
        if ( static::$is_boot ) {
            return;
        }

        static::$is_boot = true;
        $this->set_root_dir_and_url( $root_dir, $root_file );
        $this->set_config();
        $this->run_system_provider();
        $this->run_provider();
    }

    private function set_config()
    {
        static::$config = $this->get_config( 'app' );
    }

    public function get_config( string $file_name )
    {
        return $this->get_config_form_file( $file_name, $this->get_root_dir() );
    }

    private function run_system_provider()
    {
        foreach ( $this->get_system_provider() as $provider ) {
            /**
             * @var ServiceProvider $provider_object
             */
            $provider_object = new $provider( static::$instance );
            $provider_object->boot( static::$instance );
        }
    }

    public function run_provider()
    {
        if ( is_admin() ) {
            foreach ( static::$config['admin_providers'] as $provider ) {
                /**
                 * @var ServiceProvider $provider_object
                 */
                $provider_object = new $provider( static::$instance );
                $provider_object->boot( static::$instance );
            }
        }

        foreach ( static::$config['providers'] as $provider ) {
            /**
             * @var ServiceProvider $provider_object
             */
            $provider_object = new $provider( static::$instance );
            $provider_object->boot( static::$instance );
        }
    }

    private function set_root_dir_and_url( string $root_dir, string $root_file )
    {
        static::$root_dir = $root_dir;
        static::$root_url = trailingslashit( plugin_dir_url( $root_file ) );
    }

    public function get_root_dir(): string
    {
        return static::$root_dir;
    }

    public function get_root_url(): string
    {
        return static::$root_url;
    }

    /**
     * Create class and stores inside static instances variable.
     *
     * @param $class
     * @return void
     */
    public function make( $class )
    {
        if ( empty( static::$instances[$class] ) ) {
            static::$instances[$class] = new $class;
        }
        return static::$instances[$class];
    }

    private function get_system_provider()
    {
        return [
            RouteServiceProvider::class,
            EnqueueServiceProvider::class
        ];
    }
}
