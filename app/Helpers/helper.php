<?php

defined( 'ABSPATH' ) || exit;

use SmartDirectory\Bootstrap\Application;

function smart_directory_post_type()
{
    return Application::$config['post_types']['directory'];
}
