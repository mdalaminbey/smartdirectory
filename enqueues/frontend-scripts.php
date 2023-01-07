<?php

use SmartDirectory\Bootstrap\System\Utils\Common;

defined( 'ABSPATH' ) || exit;

wp_enqueue_script( 'smart-directory-pagination-js', Common::asset( 'js/simplePagination.js' ), ['jquery'], time() );
wp_enqueue_script( 'smart-directory-js', Common::asset( 'js/app.js' ), ['jquery'], time() );
wp_enqueue_style( 'smart-directory-pagination-css', Common::asset( 'css/simplePagination.css' ), [], time() );
wp_enqueue_style( 'smart-directory-tailwind', Common::asset( 'css/app.css' ), [], time() );
