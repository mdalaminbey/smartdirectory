<?php

use SmartDirectory\Bootstrap\System\Utils\Common;

defined( 'ABSPATH' ) || exit;

wp_enqueue_style( 'smart-directory-tailwind', Common::asset( 'css/app.css' ), [], time() );