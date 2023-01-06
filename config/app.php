<?php

defined( 'ABSPATH' ) || exit;

use SmartDirectory\App\Providers\Admin\DirectoryCptServiceProvider as AdminDirectoryCptServiceProvider;
use SmartDirectory\App\Providers\DirectoryCptServiceProvider;
use SmartDirectory\App\Providers\LocalizationServiceProvider;
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
        LocalizationServiceProvider::class,
        DirectoryCptServiceProvider::class,
        ShortcodeServiceProvider::class
    ],

    'admin_providers' => [
        AdminDirectoryCptServiceProvider::class
    ],
    /**
     * Plugin Api Namespace
     */
    'namespace'       => 'smart-directory',

    'api_versions'    => ['v1'],

    'middleware'      => []
];
