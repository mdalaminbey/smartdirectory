<?php

namespace SmartDirectory\Bootstrap\System\Contracts;

use SmartDirectory\Bootstrap\Application;

abstract class ServiceProvider
{
    public $application;

    public function __construct( Application $application )
    {
        $this->application = $application;
    }

    abstract public function boot();
}
