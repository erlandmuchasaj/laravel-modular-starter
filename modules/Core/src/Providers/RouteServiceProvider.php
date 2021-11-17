<?php

namespace Modules\Core\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Modules\Core\Utils\EmCms;

abstract class RouteServiceProvider extends ServiceProvider
{

    /**
     * The root namespace to assume when generating URLs to actions.
     * @var string
     */
    private string $base = EmCms::NAME;

    /**
     * The module defining the routes
     *
     * @var string
     */
    private string $module = 'core';

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
    public function boot()
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
    abstract protected function getBackRoute(): string;

    /**
     * @return string
     */
    abstract protected function getApiRoute(): string;


    /**
     * Define the routes for the application.
     *
     * @param Router $router
     * @return void
     */
    public function map(Router $router)
    {
        # rest api
        $router->group([
            'namespace' => $this->namespace,
            'prefix' => config("{$this->base}.{$this->module}.config.api_prefix"),
            'middleware' => config("{$this->base}.{$this->module}.config.middleware.api", []),
        ], function (Router $router) {
            $this->loadApiRoutes($router);
        });

        # Web API
        $router->group([
            'namespace' => $this->namespace,
            'middleware' => ['web'],
        ], function (Router $router) {
            $this->loadWebRoutes($router);
            $this->loadBackRoutes($router);
        });
    }

    /**
     * @param Router $router
     */
    private function loadWebRoutes(Router $router): void
    {
        $frontend = $this->getWebRoute();
        if ($frontend && file_exists($frontend)) {
            $router->group([
                'as' => config("{$this->base}.{$this->module}.config.web_group"),
                'prefix' => config("{$this->base}.{$this->module}.config.web_prefix"),
                'middleware' => config("{$this->base}.{$this->module}.config.middleware.web", []),
            ], function (Router $router) use ($frontend) {
                require $frontend;
            });
        }
    }

    /**
     * @param Router $router
     */
    private function loadBackRoutes(Router $router): void
    {
        $backend = $this->getBackRoute();
        if ($backend && file_exists($backend)) {
            $router->group([
                'namespace' => 'Back',
                'as' => config("{$this->base}.{$this->module}.config.back_group"),
                'prefix' => config("{$this->base}.{$this->module}.config.back_prefix"),
                'middleware' => config("{$this->base}.{$this->module}.config.middleware.back", []),
            ], function (Router $router) use ($backend) {
                require $backend;
            });
        }
    }

    /**
     * @param Router $router
     */
    private function loadApiRoutes(Router $router): void
    {
        $api = $this->getApiRoute();
        if ($api && file_exists($api)) {
            $router->group([
                'namespace' => 'Api',
                'as' => config("{$this->base}.{$this->module}.config.api_group"),
            ], function (Router $router) use ($api) {
                require $api;
            });
        }
    }
}
