<?php

namespace Modules\User\Providers;

use Modules\Core\Providers\RouteServiceProvider as CoreRouteServiceProvider;

class RouteServiceProvider extends CoreRouteServiceProvider
{
    /**
     * The root namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $namespace = 'Modules\\User\\Http\\Controllers';

    /**
     * @return string
     */
    protected function getWebRoute(): string
    {
        return __DIR__.'/../../routes/web.php';
    }

    /**
     * @return string
     */
    protected function getApiRoute(): string
    {
        return __DIR__.'/../../routes/api.php';
    }

    /**
     * @return string
     */
    protected function getChannelsRoute(): string
    {
        return __DIR__.'/../../routes/channels.php';
    }
}
