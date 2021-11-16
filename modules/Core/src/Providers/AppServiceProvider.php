<?php

namespace Modules\Core\Providers;

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
use Modules\Core\Console\Commands\AppVersion;
use Modules\Core\Console\Commands\Install;
use Modules\Core\Http\Middleware\AddXHeader;
use Modules\Core\Http\Middleware\Api\IdempotencyMiddleware;
use Modules\Core\Http\Middleware\CheckForDemoMode;
use Modules\Core\Http\Middleware\IPFireWall;
use Modules\Core\Http\Middleware\LocaleMiddleware;
use Modules\Core\Http\Middleware\RememberLocale;
use Modules\Core\Models\Announcement\Announcement;
use Modules\Core\Observers\AnnouncementObserver;
use Modules\Core\Policies\AnnouncementPolicy;
use Modules\Core\Repositories\AnnouncementRepository;
use Modules\Core\Traits\CanPublishConfiguration;

class AppServiceProvider extends ServiceProvider
{

    use CanPublishConfiguration;

    /**
     * The root namespace
     *
     * @var string
     */
    protected string $module = 'core';

    /**
     * The root namespace to assume when generating URLs to actions.
     * @var string
     */
    protected string $namespace = 'Modules\\Core\\Http\\Controllers';

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
        ConsoleServiceProvider::class,
    ];

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected array $policies = [
        Announcement::class => AnnouncementPolicy::class,
    ];

    /**
     * Boot module observers.
     *
     * @return array
     */
    protected array $observers = [
        Announcement::class => AnnouncementObserver::class,
    ];

    /**
     * The application's global middleware stack.
     *
     * @var array
     */
    protected array $middleware = [
        AddXHeader::class,
        CheckForDemoMode::class,
        LocaleMiddleware::class,
        IPFireWall::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected array $middlewareGroups = [
        'web' => [
            RememberLocale::class,
        ],
        'api' => [
            IdempotencyMiddleware::class,
        ],
    ];

    /**
     * The application's route middleware.
     * These middleware may be assigned to group or used individually.
     * @var array
     */
    protected array $routeMiddleware = [
        // 'subscription.is_customer' => hasBeenCustomer::class,
    ];


    /**
     * The available command shortname.
     *
     * @var array
     */
    protected array $commands = [
        AppVersion::class,
        Install::class,
    ];

    /**
     * Bootstrap services.
     *
     *
     * @param AnnouncementRepository $announcementRepository
     * @return void
     * @throws BindingResolutionException
     */
    public function boot(AnnouncementRepository $announcementRepository): void
    {
        // view composers
        View::composer(['frontend.layouts.app'], function ($view) use ($announcementRepository) {
            $view->with('announcements', $announcementRepository->getForFrontend());
        });

        View::composer(['backend.layouts.app'], function ($view) use ($announcementRepository) {
            $view->with('announcements', $announcementRepository->getForBackend());
        });

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

        // boot routes
        $this->bootRoutes();

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
        $this->app->scoped("{$this->base}.isInstalled", function () {
            return true === config("{$this->base}.{$this->module}.config.is_installed");
        });

        $this->app->scoped("{$this->base}.onBackend", function () {
            return $this->onBackend();
        });

        $this->app->scoped("{$this->base}.ModulesList", function () {
            return config("{$this->base}.{$this->module}.config.CoreModules");
        });

        // Publish configs
        $this->publishConfig($this->module, 'config');

        // Register Bindings
        $this->registerBindings();

        // Register Facades
        $this->registerFacades();

        // Register Commands
        $this->registerCommands();

        // Register providers
        $this->registerProviders();

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
        //
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
        Validator::extend('indisposable', 'Modules\\Core\\Validators\\Indisposable@validate');
        Validator::extend('check_domain', 'Modules\\Core\\Validators\\DomainName@validate');
        Validator::extend('extensions', function ($attribute, $value, $parameters) {
            return in_array($value->getClientOriginalExtension(), $parameters);
        });
        Validator::replacer('extensions', function ($message, $attribute, $rule, $parameters) {
            return str_replace([':attribute', ':values'], [$attribute, implode(',', $parameters)], $message);
        });

        return $this;
    }

    /**
     * bootRoutes
     * @return static
     */
    private function bootRoutes(): static
    {
        // api routes
        $api = __DIR__ . '/../../routes/api.php';
        if (file_exists($api)) {
            Route::middleware(config("{$this->base}.{$this->module}.config.middleware.api", []))
                ->as(config("{$this->base}.{$this->module}.config.api_group"))
                ->prefix(config("{$this->base}.{$this->module}.config.api_prefix"))
                ->namespace($this->namespace . '\\Api')
                ->group($api);
        }

        // web routes
        $web = __DIR__ . '/../../routes/web.php';
        if (file_exists($web)) {
            # $this->loadRoutesFrom($web);
            # The versions below give you a little more control.
            Route::middleware(config("{$this->base}.{$this->module}.config.middleware.web", []))
                ->as(config("{$this->base}.{$this->module}.config.web_group"))
                ->prefix(config("{$this->base}.{$this->module}.config.web_prefix"))
                ->namespace($this->namespace)
                ->group($web);
        }

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

        # Blade::component('package-alert', AComponent::class);

        /**
         * Set variable.
         * Usage: @set($variable, value)
         */
        Blade::directive('set', function ($expression) {
            list($variable, $value) = $this->getArguments($expression);
            return "<?php {$variable} = {$value}; ?>";
        });

        /*
         * The block of code inside this directive indicates
         * the project is currently running in read only mode.
         */
        Blade::if('demo', function () {
            return config('app.demo');
        });

        /*
         * The block of code inside this directive indicates
         * the chosen language requests RTL support.
         */
        Blade::if('rtl', function ($session_identifier = 'lang-rtl') {
            return session()->has($session_identifier);
        });


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

    /**
     * Checks if the current url matches the configured backend uri
     * @return bool
     */
    private function onBackend(): bool
    {
        $url = app(Request::class)->path();
        // "{$this->base}.{$this->module}.config.backend_prefix"
        if (str_contains($url, config("{$this->base}.{$this->module}.config.backend_prefix"))) {
            return true;
        }

        return false;
    }

    /**
     * Get argument array from argument string.
     * @param string $argumentString
     * @return array
     */
    private function getArguments(string $argumentString): array
    {
        return str_getcsv($argumentString, ',', "'");
    }

    /**
     * @param string $file
     * @return string
     */
    private function getConfigFilename(string $file): string
    {
        return strval(preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($file)));
    }

}
