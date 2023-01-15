<?php

namespace SmartDirectory\Bootstrap\System\Contracts;

defined( 'ABSPATH' ) || exit;

use SmartDirectory\Bootstrap\Application;

abstract class ServiceProvider
{
    public $application;

    public function __construct( Application $application )
    {
        $this->application = $application;
    }

    /**
	 * The boot method is called immediately after the service provider calls the constructor
	 *
	 * @return void
	 */
    abstract public function boot();
}
