<?php

use SmartDirectory\App\Http\Controllers\DirectoryController;
use SmartDirectory\Bootstrap\System\Route\Route;

defined( 'ABSPATH' ) || exit;

Route::group( 'directory', function () {
    Route::post( 'create', [DirectoryController::class, 'create'] );
} );
