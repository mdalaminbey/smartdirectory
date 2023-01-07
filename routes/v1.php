<?php

use SmartDirectory\App\Http\Controllers\DirectoryController;
use SmartDirectory\Bootstrap\System\Route\Route;

defined( 'ABSPATH' ) || exit;

Route::post( 'directory', [DirectoryController::class, 'index'] );

Route::group( ['prefix' => 'directory', 'middleware' => ['is_user_logged']], function () {
    Route::post( 'create', [DirectoryController::class, 'create'] );
    Route::post( 'user-directories', [DirectoryController::class, 'user_directories'] );
});
