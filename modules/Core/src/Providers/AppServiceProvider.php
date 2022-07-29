<?php

namespace Modules\Core\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\CachesRoutes;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

use Modules\Core\Traits\CanPublishConfiguration;
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
use Modules\Core\View\Components\AppLayout;
use Modules\Core\View\Components\GuestLayout;
use Spatie\Honeypot\ProtectAgainstSpam;


class AppServiceProvider extends ServiceProvider
{

    use CanPublishConfiguration;

    /**
     * The Module Name
     *
     * @var string
     */
    protected string $module = 'Core';

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
        RouteServiceProvider::class,
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
     * register module aliases.
     *
     * @return array
     */
    protected  array $aliases = [
        'announcement' => Announcement::class,
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
            ProtectAgainstSpam::class, # we can set it also as global middleware.
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
     * Bootstrap your package's services.
     *
     *
     * @param AnnouncementRepository $announcementRepository
     * @return void
     * @throws BindingResolutionException
     */
    public function boot(AnnouncementRepository $announcementRepository): void
    {

        // dd($this->module(), $this->module(true), GuestLayout::class);

        logger('AppServiceProvider::boot => '. $this->module);

        // Create custom rate limiters
        // to use @example: Route::middleware(['throttle:uploads'])
        RateLimiter::for('uploads', function (Request $request) {
            return $request->user()
                ? Limit::perMinute(100)->by($request->user()->id)
                : Limit::perMinute(10)->by($request->ip());
        });

        // # view composers
        // View::composer(['frontend.layouts.app'], function ($view) use ($announcementRepository) {
        //     $view->with('announcements', $announcementRepository->getForFrontend());
        // });
        //
        // View::composer(['backend.layouts.app'], function ($view) use ($announcementRepository) {
        //     $view->with('announcements', $announcementRepository->getForBackend());
        // });

        # This will allow the usage of package components by their vendor namespace using the package-name:: syntax.
        # ex: <x-core::calendar /> <x-core::alert /> <x-core::forms.input /> # for sub directories.
        Blade::componentNamespace('\\Modules\\Core\\Views\\Components', 'core'); # does not work
        // Blade::component('app-layout', AppLayout::class); // Works
        // Blade::component('guest-layout', GuestLayout::class); // Works

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
        $this->bootServices();

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
    public function register(): void
    {
        logger('AppServiceProvider::register => '. $this->module);


        $this->app->scoped("{$this->base}.isInstalled", function ($app) {
            return true === config("{$this->base}.{$this->module(true)}.config.is_installed");
        });

        $this->app->scoped("{$this->base}.onBackend", function ($app) {
            return $this->onBackend();
        });

        $this->app->scoped("{$this->base}.ModulesList", function () {
            return config("{$this->base}.{$this->module(true)}.config.CoreModules");
        });

        // Publish configs
        $this->publishConfig($this->module(true), 'config');

        // Register Bindings
        # $this->registerBindings();

        // Register Facades
        # $this->registerFacades();

        // Register Aliases
        # $this->registerAliases();

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
     * Register Facades.
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

        //        $this->app->scoped('core', function () {
        //            return app()->make(Core::class);
        //        });
    }

    /**
     * Register Aliases.
     *
     * @return void
     */
    public function registerAliases(): void
    {
        $loader = AliasLoader::getInstance();
        foreach ( (array) $this->aliases as $aliasName => $aliasClass) {
            $loader->alias($aliasName, $aliasClass);
        }
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
        foreach ((array) $this->policies as $className => $policyName) {
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
        foreach ((array) $this->observers as $className => $observerName) {
            $classObj = app($className);
            if (!is_null($classObj)) {
                $classObj::observe($observerName);
            }
        }

        return $this;
    }

    /**
     * bootServices
     * @return void
     */
    private function bootServices(): void
    {
        // Boot your services here
    }

    /**
     * boot migrations.
     * @return static
     * @todo check
     */
    private function bootMigrations(): static
    {
        # $path = __DIR__ . '/../../database/migrations';

        $path = base_path('modules' . DIRECTORY_SEPARATOR . $this->module() . DIRECTORY_SEPARATOR . 'database'. DIRECTORY_SEPARATOR . 'migrations');

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
     * @todo check
     */
    private function bootViews(): static
    {
        # $path = __DIR__ . '/../../resources/views';

        $path = base_path('modules' . DIRECTORY_SEPARATOR . $this->module() . DIRECTORY_SEPARATOR . 'resources'. DIRECTORY_SEPARATOR . 'views');

        $this->loadViewsFrom($path, $this->module(true));

        if ($this->app->runningInConsole()) {
            $this->publishes([
                $path => resource_path("views/vendor/{$this->base}/{$this->module(true)}"),
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

        $path = base_path('modules' . DIRECTORY_SEPARATOR . $this->module() . DIRECTORY_SEPARATOR . 'resources'. DIRECTORY_SEPARATOR . 'lang');

        $this->loadTranslationsFrom($path, $this->module(true));

        if ($this->app->runningInConsole()) {
            $this->publishes([
                $path => lang_path("vendor/{$this->module(true)}"),
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

            // # $path = base_path('modules' . DIRECTORY_SEPARATOR . $this->module() . DIRECTORY_SEPARATOR .
            // 'database' . DIRECTORY_SEPARATOR . 'factories');
            // # $this->loadFactoriesFrom($path);

            if ($this->app->runningInConsole()) {
                $this->publishes([
                    __DIR__ . '/../../database/seeders/DatabaseSeeder.php' => database_path('seeders/' . $this->module() . 'ModuleSeeder.php'),
                ], 'seeders');
            }
        }

        return $this;
    }

    /**
     * Get module case according to different usage cases.
     * Studly or snake case.
     * @param bool $snake
     * @return string
     */
    private function module(bool $snake = false): string
    {
        if ($snake === true){
            return Str::snake($this->module);
        }
        return Str::studly($this->module);
    }

    /**
     * Checks if the current url matches the configured backend uri
     * @return bool
     */
    private function onBackend(): bool
    {
        $url = app(Request::class)->path();
        // "{$this->base}.{$this->module}.config.backend_prefix"
        if (Str::contains($url, config("{$this->base}.{$this->module(true)}.config.backend_prefix"))) {
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
