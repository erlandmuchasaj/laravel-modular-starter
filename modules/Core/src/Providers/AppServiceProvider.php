<?php

namespace Modules\Core\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Modules\Core\Console\Commands\AppVersion;
use Modules\Core\Console\Commands\Install;
use Modules\Core\Http\Middleware\AddXHeader;
use Modules\Core\Http\Middleware\Api\IdempotencyMiddleware;
use Modules\Core\Http\Middleware\CheckForDemoMode;
use Modules\Core\Http\Middleware\IPFireWall;
use Modules\Core\Http\Middleware\IpWhitelist;
use Modules\Core\Http\Middleware\LocaleMiddleware;
use Modules\Core\Http\Middleware\RememberLocale;
use Modules\Core\Models\Announcement\Announcement;
use Modules\Core\Observers\AnnouncementObserver;
use Modules\Core\Policies\AnnouncementPolicy;
use Spatie\Honeypot\ProtectAgainstSpam;

class AppServiceProvider extends BaseAppServiceProvider
{
    /**
     * The CamelCased module name
     *
     * @var string
     */
    protected string $module = 'Core';

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
    protected array $aliases = [
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
            ProtectAgainstSpam::class, // we can set it also as global middleware.
            RememberLocale::class,
        ],
        'api' => [
            IdempotencyMiddleware::class,
        ],
    ];

    /**
     * The application's route middleware.
     * These middleware may be assigned to group or used individually.
     *
     * @var array
     */
    protected array $routeMiddleware = [
        'ip_whitelist' => IpWhitelist::class
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
     * @return void
     *
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        logger('AppServiceProvider::boot => '.$this->module);
        // Create custom rate limiters
        // to use @example: Route::middleware(['throttle:uploads'])
        RateLimiter::for('uploads', function (Request $request) {
            return $request->user()
                ? Limit::perMinute(100)->by($request->user()->id)
                : Limit::perMinute(10)->by($request->ip());
        });

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

        $this->app->scoped("{$this->base}.isInstalled", function ($app) {
            return true === config("{$this->base}.{$this->module(true)}.config.is_installed");
        });

        $this->app->scoped("{$this->base}.onBackend", function ($app) {
            return $this->onBackend();
        });

        $this->app->scoped("{$this->base}.ModulesList", function () {
            return config("{$this->base}.{$this->module(true)}.config.CoreModules");
        });


        parent::register();
    }

    /**
     * bootValidators
     *
     * @return void
     */
    protected function bootValidators(): void
    {
        Validator::extend('indisposable', 'Modules\\Core\\Validators\\Indisposable@validate');
        Validator::extend('check_domain', 'Modules\\Core\\Validators\\DomainName@validate');
        Validator::extend('extensions', function ($attribute, $value, $parameters) {
            return in_array($value->getClientOriginalExtension(), $parameters);
        });
        Validator::replacer('extensions', function ($message, $attribute, $rule, $parameters) {
            return str_replace([':attribute', ':values'], [$attribute, implode(',', $parameters)], $message);
        });

        parent::bootValidators();
    }

    /**
     * bootBladeDirective
     *
     * @return void
     */
    protected function bootBladeDirective(): void
    {
        if (app()->environment() === 'testing') {
            logger('This is running tests!');
        }

        /**
         * Set variable.
         * Usage: @set($variable, value)
         */
        Blade::directive('set', function ($expression) {
            [$variable, $value] = $this->getArguments($expression);

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

        parent::bootBladeDirective();
    }

    /**
     * Get argument array from argument string.
     *
     * @param  string  $argumentString
     * @return array
     */
    private function getArguments(string $argumentString): array
    {
        return str_getcsv($argumentString, ',', "'");
    }

    /**
     * Checks if the current url matches the configured backend uri
     *
     * @return bool
     */
    private function onBackend(): bool
    {
        $url = app(Request::class)->path();
        if (Str::contains($url, config("$this->base.{$this->module(true)}.config.backend_prefix"))) {
            return true;
        }

        return false;
    }
}
