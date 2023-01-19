<?php

namespace SmartDirectory\App\Providers;

defined( 'ABSPATH' ) || exit;

use SmartDirectory\Bootstrap\System\Contracts\ServiceProvider;
use SmartDirectory\Bootstrap\System\Utils\Common;
use SmartDirectory\Bootstrap\System\View\View;

class BlockRegisterServiceProvider extends ServiceProvider {

	public function boot() {
		add_action( 'init', array( $this, 'action_init' ) );
	}

	/**
	 * Fires after WordPress has finished loading but before any headers are sent.
	 */
	public function action_init(): void {

		$asset_file = include_once $this->application::$instance->get_root_dir() . '/assets/js/block.asset.php';

		$block_list = $this->application::$instance->get_config( 'block' );

		wp_register_script( 'smart-directory-block', Common::asset( 'js/block.js' ), $asset_file['dependencies'], $asset_file['version'], true );
		wp_localize_script(
			'smart-directory-block',
			'Block',
			array(
				'items' => wp_json_encode( $block_list ),
			)
		);

		foreach ( $block_list as $key => $block ) {
			register_block_type(
				'smart-directory/' . $key,
				array(
					'api_version'     => 2,
					'editor_script'   => 'smart-directory-block',
					'render_callback' => function ( $block_attributes, $content ) use ( $block ) {
						ob_start();
						View::render( $block['view_file'] );
						return ob_get_clean();
					},
				)
			);
		}
	}
}
