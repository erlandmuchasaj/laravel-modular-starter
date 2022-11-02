<?php

namespace Modules\User\Providers;

use Modules\Core\Providers\BaseSeedServiceProvider;

class SeedServiceProvider extends BaseSeedServiceProvider
{
    /**
     * The root namespace to assume where to get the seeding data from.
     * Inside the DatabaseSeeder folder you can create as many
     * as you want seeders and call them inside it.
     * $this->call(ModelTableSeeder::class);
     *
     * @var string
     */
    protected string $namespace = 'Modules\\User\\Database\\Seeders\\DatabaseSeeder';
}
