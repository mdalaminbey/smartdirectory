<?php

namespace SmartDirectory\App\Providers;

defined( 'ABSPATH' ) || exit;

use SmartDirectory\Bootstrap\System\Contracts\ServiceProvider;

class LocalizationServiceProvider extends ServiceProvider
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
        add_action( 'wp_head', [$this, 'action_wp_head'] );
    }

    /**
     * Prints scripts or data in the head tag on the front end.
     *
     */
    public function action_wp_head(): void
    {
        $args = [
            'root' => esc_url_raw( rest_url() ),
            'nonce' => wp_create_nonce( 'wp_rest' )
        ];
    ?>
        <script>
            var SmartDirectorySettings = <?php echo json_encode( $args )?>
        </script>
    <?php
    }
}
