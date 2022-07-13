<?php

namespace Modules\User\Providers;

use Illuminate\Console\Events\CommandFinished;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Modules\Core\Providers\BaseSeedServiceProvider;
use Symfony\Component\Console\Output\ConsoleOutput;

class SeedServiceProvider extends BaseSeedServiceProvider
{

    /**
     * The root namespace to assume where to get the seeding data from.
     *
     * @var string
     */
    protected string $namespace = 'Modules\\User\\Database\\Seeders';

}
