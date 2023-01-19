<?php

namespace SmartDirectory\App\Providers;

defined( 'ABSPATH' ) || exit;

use SmartDirectory\Bootstrap\System\Contracts\ServiceProvider;
use SmartDirectory\Bootstrap\System\View\View;

class ShortcodeServiceProvider extends ServiceProvider {

	public function boot() {
		add_action( 'init', array( $this, 'action_init' ) );
	}

	/**
	 * Fires after WordPress has finished loading but before any headers are sent.
	 */
	public function action_init(): void {

		add_shortcode( 'smart-directory-form', array( $this, 'form_view' ) );
		add_shortcode( 'smart-directory-listings', array( $this, 'listings_view' ) );
		add_shortcode( 'smart-directory-user-listings', array( $this, 'user_listings' ) );
	}

	public function form_view() {

		ob_start();
		View::render( 'frontend/form' );
		return ob_get_clean();
	}

	public function listings_view() {

		ob_start();
		View::render( 'frontend/directories/index' );
		return ob_get_clean();
	}

	public function user_listings() {

		ob_start();
		View::render( 'frontend/user-directories/index' );
		return ob_get_clean();
	}
}
