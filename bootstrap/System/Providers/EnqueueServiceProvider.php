<?php

namespace SmartDirectory\Bootstrap\System\Providers;

defined( 'ABSPATH' ) || exit;

use SmartDirectory\Bootstrap\System\Contracts\ServiceProvider;

final class EnqueueServiceProvider extends ServiceProvider
{
    public function boot()
    {
        add_action( 'admin_enqueue_scripts', [$this, 'action_admin_enqueue_scripts'] );
        add_action( 'wp_enqueue_scripts', [$this, 'action_wp_enqueue_scripts'] );
    }

    /**
     * Enqueue scripts for all admin pages.
     *
     * @param string $hook_suffix The current admin page.
     */
    public function action_admin_enqueue_scripts( string $hook_suffix ): void
    {
        $application = $this->application;
        include_once $application->get_root_dir() . '/enqueues/admin-scripts.php';
    }

    /**
     * Fires when scripts and styles are enqueued.
     *
     */
    public function action_wp_enqueue_scripts(): void
    {
        $application = $this->application;
        include_once $application->get_root_dir() . '/enqueues/frontend-scripts.php';
    }

}
