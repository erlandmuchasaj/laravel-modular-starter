<?php

namespace Modules\Core\Providers;

use Illuminate\Contracts\Foundation\CachesRoutes;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Core\Utils\EmCms;

abstract class BaseRouteServiceProvider extends ServiceProvider
{

    /**
     * The base platform name.
     * This SHOULD NOT be changed.
     *
     * @var string
     */
    private string $base = EmCms::NAME; /** @phpstan-ignore-line */

    /**
     * The module defining the routes
     *
     * @var string
     */
    private string $module = 'core'; /** @phpstan-ignore-line */

    /**
     * The root namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $namespace = '';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * @return string
     */
    abstract protected function getWebRoute(): string;

    /**
     * @return string
     */
    abstract protected function getApiRoute(): string;

    /**
     * @return string
     */
    abstract protected function getChannelsRoute(): string;


    /**
     * Define the routes for the application.
     *
     * @param Router $router
     * @return void
     */
    public function map(Router $router): void
    {
        // If the routes have not been cached, we will include them in a route group
        // so that all the routes will be conveniently registered to the given
        // controller namespace. After that we will load the EMCMS routes file.



        if (! ($this->app instanceof CachesRoutes && $this->app->routesAreCached())) {
            # mapApiRoutes
            $router->group([
                'prefix' => 'api',
                'middleware' => ['api'],
                'namespace' => $this->namespace,
            ], function (Router $router) {
                $this->loadApiRoutes($router);
            });

            # mapWebRoutes
            $router->group([
                'middleware' => ['web'],
                'namespace' => $this->namespace,
            ], function (Router $router) {
                $this->loadWebRoutes($router);
            });
        }

        # Channels
        $this->loadChannelsRoutes();

    }

    /**
     * Load all web routes.
     * @param Router $router
     */
    private function loadWebRoutes(Router $router): void
    {
        $frontend = $this->getWebRoute();
        if ($frontend && file_exists($frontend)) {
            $router->group([], function (Router $router) use ($frontend) {
                require $frontend;
            });
        }
    }

    /**
     * Load /Api routes
     * @param Router $router
     */
    private function loadApiRoutes(Router $router): void
    {
        $api = $this->getApiRoute();
        if ($api && file_exists($api)) {
            $router->group([
                'namespace' => 'Api',
                'as' => 'api.',
            ], function (Router $router) use ($api) {
                require $api;
            });
        }
    }

    /**
     * Load BroadCast Channel routes
     */
    private function loadChannelsRoutes(): void
    {
        $channels = $this->getChannelsRoute();
        if ($channels && file_exists($channels)) {
            require $channels;
        }
    }

}
