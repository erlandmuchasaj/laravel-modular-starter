<?php

namespace Modules\Example\Providers;

use Modules\Core\Providers\RouteServiceProvider as CoreRouteServiceProvider;

class RouteServiceProvider extends CoreRouteServiceProvider
{

    /**
     * The root namespace to assume when generating URLs to actions.
     * @var string
     */
    protected $namespace = 'Modules\\Example\\Http\\Controllers';

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
    protected function getBackRoute(): string
    {
        return __DIR__.'/../../routes/backend.php';
    }

    /**
     * @return string
     */
    protected function getApiRoute(): string
    {
        return __DIR__.'/../../routes/api.php';
    }


}
