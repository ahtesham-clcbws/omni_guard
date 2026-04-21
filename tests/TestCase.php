<?php

namespace OmniGuard\Tests;

use OmniGuard\OmniGuardServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            OmniGuardServiceProvider::class,
        ];
    }
}
