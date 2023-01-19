<?php

namespace SmartDirectory\Bootstrap\System\Utils;

defined( 'ABSPATH' ) || exit;

use SmartDirectory\Bootstrap\Application;

class Common {

	/**
	 * Get asset director
	 *
	 * @param string $asset file path without asset.
	 * @return string
	 */
	public static function asset( $asset = '' ) {
		return Application::$instance->get_root_url() . 'assets/' . trim( $asset, '/' );
	}

	/**
	 * Get plugin currency version
	 *
	 * @return string
	 */
	public static function version() {
		return Application::$instance::$config['version'];
	}

	/**
	 * Encode json for html attribute.
	 *
	 * @param array $data
	 * @return string
	 */
	public static function json_encode_for_attr( array $data ) {
		return htmlspecialchars( wp_json_encode( $data ), ENT_QUOTES, 'UTF-8' );
	}

	/**
	 * Add params to the URL.
	 *
	 * @param string $url
	 * @param array  $params Ex: ['key' => 'value', 'key1' => 'value1'].
	 * @return string
	 */
	public static function url_add_params( string $url, array $params ) {

		$query     = wp_parse_url( $url, PHP_URL_QUERY );
		$url_param = '';
		$i         = 0;

		foreach ( $params as $name => $value ) {
			if ( 0 === $i ) {
				$url_param .= $name . '=' . $value;
			} else {
				$url_param .= '&' . $name . '=' . $value;
			}
			$i++;
		}

		if ( $query ) {
			$url .= '&' . $url_param;
		} else {
			$url .= '?' . $url_param;
		}

		return $url;
	}

	/**
	 * Using the method you can check the admin's current page inside any hook. no matter whether the wp is fully loaded or not.
	 *
	 * @param string $file_name WordPress url current file name. Like:- admin, edit, plugins.
	 * @param array  $params Current url available get method request params: Like: page, post_type. Ex:- ['post_type' => 'page'].
	 * @return boolean
	 */
	public static function is_admin_page( string $file_name = 'admin', array $params = array() ): bool {

		if ( empty( $_SERVER['REQUEST_URI'] ) ) {
			return false;
		}
        //phpcs:ignore -- Here we check the admin's current page. not saving any data
        $pathinfo = pathinfo( $_SERVER['REQUEST_URI'] );
        //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$request = $_REQUEST;

		if ( strpos( $pathinfo['filename'], 'php' ) ) {
			$pathinfo['filename'] = explode( '.', $pathinfo['filename'] )[0];
		}

		$is_current_file = $pathinfo['filename'] === $file_name || false;

		if ( $is_current_file ) {
			foreach ( $params as $key => $value ) {
				if ( is_int( strpos( $value, '!' ) ) ) {
					if ( isset( $request[ $key ] ) && ltrim( $value, '!' ) === $request[ $key ] ) {
						return false;
					}
				} elseif ( empty( $request[ $key ] ) || $request[ $key ] !== $value ) {
					return false;
				}
			}
			return true;
		}

		return false;
	}
}
