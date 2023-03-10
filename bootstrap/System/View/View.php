<?php

namespace SmartDirectory\Bootstrap\System\View;

defined( 'ABSPATH' ) || exit;

use SmartDirectory\Bootstrap\Application;

class View {

	public static function render( string $path, array $args = array() ) {
		extract( $args );
		include self::get_path( $path );
	}

	public static function send( string $path, array $args = array() ) {
		extract( $args );
		ob_start();
		include self::get_path( $path );
		ob_flush();
	}

	protected static function get_path( string $path ) {

		$application = Application::$instance;

		if ( isset( pathinfo( $path )['extension'] ) ) {
			return $application->get_root_dir() . '/resources/views/' . $path;
		} else {
			return $application->get_root_dir() . '/resources/views/' . $path . '.php';
		}
	}
}
