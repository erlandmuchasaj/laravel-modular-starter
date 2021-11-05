<?php

namespace Modules\Core\Tests;

use  Modules\Core\Providers\AppServiceProvider;

class TestCase
{
    public function setUp(): void
    {
        // additional setup
    }

    protected function getPackageProviders(): array
    {
        return [
            AppServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp(): void
    {
        // perform environment setup
    }
}
