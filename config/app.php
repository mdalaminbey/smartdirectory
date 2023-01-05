<?php

defined( 'ABSPATH' ) || exit;

use SmartDirectory\App\Providers\DirectoryCptServiceProvider;
use SmartDirectory\App\Providers\ShortcodeServiceProvider;

return [
    /**
     * Plugin Current Version
     */
    'version'         => '1.0.0',

    'post_types'      => [
        'directory' => 'directory'
    ],

    /**
     * Service providers
     */
    'providers'       => [
        DirectoryCptServiceProvider::class,
        ShortcodeServiceProvider::class
    ],

    'admin_providers' => [],
    /**
     * Plugin Api Namespace
     */
    'namespace'       => 'smart-directory',

    'api_versions'    => [],

    'middleware'      => ['v1']
];
