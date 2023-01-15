<?php
/**
 * Register version 1 all rest api
 *
 * @package  SmartDirectory
 * @license  https://www.gnu.org/licenses/gpl-3.0.en.html GNU Public License
 * @since    1.0.0
 */

use SmartDirectory\App\Http\Controllers\Admin\DirectoryController as AdminDirectoryController;
use SmartDirectory\App\Http\Controllers\DirectoryController;
use SmartDirectory\Bootstrap\System\Route\Route;

defined( 'ABSPATH' ) || exit;


/**
 * Frontend rest api
 */
Route::get( 'directories', array( DirectoryController::class, 'index' ) );

Route::group(
	array(
		'prefix'     => 'directories',
		'middleware' => array( 'is_user_logged' ),
	),
	function () {
		Route::post( '/', array( DirectoryController::class, 'create' ) );
		Route::get( 'user-directories', array( DirectoryController::class, 'user_directories' ) );
	}
);

/**
 * Admin rest api
 */
Route::group(
	array(
		'prefix'     => 'admin',
		'middleware' => array( 'is_admin' ),
	),
	function () {
		Route::post( 'directories', array( AdminDirectoryController::class, 'create' ) );
		Route::patch( 'directories', array( AdminDirectoryController::class, 'update' ) );
	}
);
