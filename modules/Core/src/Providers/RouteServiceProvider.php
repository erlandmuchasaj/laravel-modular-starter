<?php

namespace Modules\Core\Providers;

use ErlandMuchasaj\Modules\Providers\BaseRouteServiceProvider;

class RouteServiceProvider extends BaseRouteServiceProvider
{
    /**
     * The root namespace to assume when generating URLs to actions.
     *
     * @var string|null
     */
    protected $namespace = 'Modules\\Core\\Http\\Controllers';

    protected function getWebRoute(): string
    {
        return __DIR__.'/../../routes/web.php';
    }

    protected function getApiRoute(): string
    {
        return __DIR__.'/../../routes/api.php';
    }

    protected function getChannelsRoute(): string
    {
        return __DIR__.'/../../routes/channels.php';
    }
}
