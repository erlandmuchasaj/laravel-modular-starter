<?php

namespace Modules\User\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Laravel\Sanctum\SanctumServiceProvider;
use Modules\Core\Providers\BaseAppServiceProvider;

class AppServiceProvider extends BaseAppServiceProvider
{
    /**
     * The Module Name
     *
     * @var string
     */
    protected string $module = 'User';

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, class-string>
     */
    protected array $providers = [
        RouteServiceProvider::class,
        EventServiceProvider::class,
        SeedServiceProvider::class,
        SanctumServiceProvider::class,
    ];

    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected array $policies = [
    ];

    /**
     * Boot module observers.
     *
     * @return array<class-string, class-string>
     */
    protected array $observers = [
    ];

    /**
     * register module aliases.
     *
     * @example 'alias' => Model::class
     *
     * @return array<string, class-string>
     */
    protected array $aliases = [
    ];

    /**
     * The application's global middleware stack.
     *
     * @var array<int, class-string>
     */
    protected array $middleware = [
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string>>
     */
    protected array $middlewareGroups = [
        'web' => [
        ],
        'api' => [
        ],
    ];

    /**
     * The application's route middleware.
     * These middleware may be assigned to group or used individually.
     *
     * @var array<string, class-string>
     */
    protected array $routeMiddleware = [
    ];

    /**
     * The available command shortname.
     *
     * @var array<int, class-string>
     */
    protected array $commands = [
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     *
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        logger('AppServiceProvider::boot => '.$this->module);

        parent::boot();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        logger('AppServiceProvider::register => '.$this->module);

        parent::register();
    }
}
