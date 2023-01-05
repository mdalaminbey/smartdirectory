<?php

namespace SmartDirectory\App\Providers;

defined( 'ABSPATH' ) || exit;

use SmartDirectory\Bootstrap\System\Contracts\ServiceProvider;
use SmartDirectory\Bootstrap\System\View\View;

class ShortcodeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        add_action( 'init', [$this, 'action_init'] );
    }

    /**
     * Fires after WordPress has finished loading but before any headers are sent.
     *
     */
    public function action_init(): void
    {
        add_shortcode( 'super-directory-form', [$this, 'form_view'] );
        add_shortcode( 'super-directory-listings', [$this, 'listings_view'] );
    }

    public function form_view()
    {
        ob_start();
        View::render( 'shortcode/form' );
        return ob_get_clean();
    }

    public function listings_view()
    {
        ob_start();
        View::render( 'shortcode/listings' );
        return ob_get_clean();
    }
}
