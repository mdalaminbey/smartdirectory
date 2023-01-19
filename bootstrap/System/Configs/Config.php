<?php

namespace SmartDirectory\Bootstrap\System\Configs;

defined( 'ABSPATH' ) || exit;

abstract class Config {

	/**
	 * Store config directory file information
	 *
	 * @var array
	 */
	protected static $configs = array();

	protected function get_config_form_file( string $file_name, string $plugin_root_dir ) {

		if ( isset( static::$configs[ $file_name ] ) ) {
			return static::$configs[ $file_name ];
		}

		$file_path = $plugin_root_dir . '/config/' . $file_name . '.php';

		if ( is_file( $file_path ) ) {
			$config_data                   = include_once $file_path;
			static::$configs[ $file_name ] = $config_data;
			return $config_data;
		}

		return false;
	}
}
