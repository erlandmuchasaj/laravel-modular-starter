<?php

namespace Modules\Example\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

use Modules\Core\Traits\CanPublishConfiguration;

class AppServiceProvider extends ServiceProvider
{

    use CanPublishConfiguration;

    /**
     * The Module Name
     *
     * @var string
     */
    protected string $module = 'Example';

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected bool $defer = false;

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    protected array $providers = [
        EventServiceProvider::class,
        SeedServiceProvider::class,
    ];

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected array $policies = [
    ];

    /**
     * Boot module observers.
     *
     * @return array
     */
    protected array $observers = [
    ];

    /**
     * The application's global middleware stack.
     *
     * @var array
     */
    protected array $middleware = [
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
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
     * @var array
     */
    protected array $routeMiddleware = [
    ];


    /**
     * The available command shortname.
     *
     * @var array
     */
    protected array $commands = [
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        // publish migrations
        $this->bootMigrations();

        // boot translations
        $this->bootTranslations();

        // boot Blade directive and components
        $this->bootBladeDirective();

        // boot Validators
        $this->bootValidators();

        // boot middleware
        $this->bootMiddleware();

        // boot observers
        $this->bootObservers();

        // boot Services
        // $this->bootServices();

        // boot Factories
        $this->bootFactories();

        // boot Policies
        $this->bootPolicies();

        // boot Views
        $this->bootViews();

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Publish configs
        $this->publishConfig($this->module, 'config');

        // Register providers
        $this->registerProviders();

        // Register Commands
        $this->registerCommands();

        // Register Bindings
        $this->registerBindings();

        // Register Facades
        $this->registerFacades();

    }

    /**
     * registerBindings
     *
     * @return void
     */
    private function registerBindings(): void
    {
        // $this->app->bind(
        //     CoreRepositoryInterface::class,
        //     CoreEloquentRepository::class
        // );
    }

    /**
     * Register Bouncer as a singleton.
     *
     * @return void
     */
    private function registerFacades(): void
    {
        //        $loader = AliasLoader::getInstance();
        //        $loader->alias('core', CoreFacade::class);
        //        $this->app->singleton('core', function () {
        //            return app()->make(Core::class);
        //        });
    }

    /**
     * registerCommands
     * @return void
     */
    private function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }
    }

    /**
     * registerProviders
     * @return void
     */
    private function registerProviders(): void
    {
        foreach ((array)$this->providers as $provider) {
            $this->app->register($provider);
        }
    }

    /**
     * bootMiddleware
     *
     * @return static
     * @throws BindingResolutionException
     */
    private function bootMiddleware(): static
    {
        # Register global middleware
        $kernel = $this->app->make(Kernel::class);
        foreach ((array)$this->middleware as $middleware) {
            $kernel->pushMiddleware($middleware);
        }

        $router = $this->app->make(Router::class);

        # Register route middleware
        foreach ((array)$this->routeMiddleware as $name => $class) {
            $router->aliasMiddleware($name, $class);
            # $this->app['router']->aliasMiddleware($name, $class);
        }

        # Register group middleware
        foreach ((array)$this->middlewareGroups as $group => $middlewares) {
            foreach ($middlewares as $middleware) {
                $router->pushMiddlewareToGroup($group, $middleware);
                # $this->app['router']->pushMiddlewareToGroup($group, $middleware);
            }
        }
        return $this;
    }

    /**
     * bootValidators
     * @return static
     */
    private function bootValidators(): static
    {
        return $this;
    }


    /**
     * bootBladeDirective
     *
     * @return static
     * @todo
     */
    private function bootBladeDirective(): static
    {
        if (app()->environment() === 'testing') {
            return $this;
        }

        return $this;
    }

    /**
     * bootPolicies
     * @return $this
     */
    private function bootPolicies(): static
    {
        # Gate::policy(Announcement::class, AnnouncementPolicy::class);
        foreach ($this->policies as $className => $policyName) {
            Gate::policy($className, $policyName);
        }

        return $this;
    }

    /**
     * bootObservers
     * @return $this
     */
    private function bootObservers(): static
    {
        # Announcement::observe(AnnouncementObserver::class);
        foreach ($this->observers as $className => $observerName) {
            $classObj = app($className);
            if (!is_null($classObj)) {
                $classObj::observe($observerName);
            }
        }

        return $this;
    }

    /**
     * bootServices
     * @return static
     */
    private function bootServices(): static
    {
        //
        return $this;
    }

    /**
     * boot migrations.
     * @return static
     * @todo check
     */
    private function bootMigrations(): static
    {
        # $path = __DIR__ . '/../../database/migrations';

        $path = base_path('modules' . DIRECTORY_SEPARATOR . ucfirst($this->module) . DIRECTORY_SEPARATOR . 'database'. DIRECTORY_SEPARATOR . 'migrations');

        $this->loadMigrationsFrom($path);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                $path => database_path('migrations'),
            ], 'migrations');
        }

        return $this;
    }

    /**
     * Register views & Publish views.
     * @return static
     * @todo
     */
    private function bootViews(): static
    {
        # $path = __DIR__ . '/../../resources/views';

        $path = base_path('modules' . DIRECTORY_SEPARATOR . Str::ucfirst($this->module) . DIRECTORY_SEPARATOR . 'resources'. DIRECTORY_SEPARATOR . 'views');

        $this->loadViewsFrom($path, Str::lower($this->module));

        if ($this->app->runningInConsole()) {
            $this->publishes([
                $path => resource_path("views/vendor/{$this->base}/{$this->module}"),
            ], 'views');
        }

        return $this;
    }

    /**
     * Register & Publish translations.
     *
     * Package translations are referenced using the module::file.line syntax convention
     * So, you may load the user module's welcome line from the messages file like so:
     * echo trans('user::messages.welcome');
     *
     * @return static
     */
    private function bootTranslations(): static
    {
        # $path = __DIR__ . '/../../resources/lang';

        $path = base_path('modules' . DIRECTORY_SEPARATOR . Str::ucfirst($this->module) . DIRECTORY_SEPARATOR . 'resources'. DIRECTORY_SEPARATOR . 'lang');

        $this->loadTranslationsFrom($path, Str::lower($this->module));

        if ($this->app->runningInConsole()) {
            $this->publishes([
                $path => resource_path("lang/vendor/{$this->module}"),
            ], 'lang');
        }

        return $this;
    }

    /**
     * Register an additional directory of factories.
     *
     * @return static
     */
    private function bootFactories(): static
    {
        if ($this->app->isLocal()) {

            $path = base_path('modules' . DIRECTORY_SEPARATOR . Str::ucfirst($this->module) . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'factories');
            # $this->loadFactoriesFrom($path);
            if ($this->app->runningInConsole()) {
                $this->publishes([
                    __DIR__ . '/../../database/seeds/DatabaseSeeder.php' => database_path('seeds/' . Str::ucfirst($this->module) . 'ModuleSeeder.php'),
                ], 'seeds');
            }
        }

        return $this;
    }

    private


}
