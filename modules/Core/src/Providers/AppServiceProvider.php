<?php

namespace Modules\Core\Providers;

use ErlandMuchasaj\Modules\Providers\BaseAppServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Modules\Core\Http\Middleware\AddXHeader;
use Modules\Core\Http\Middleware\Api\IdempotencyMiddleware;
use Modules\Core\Http\Middleware\CheckForDemoMode;
use Modules\Core\Http\Middleware\GzipEncodeResponse;
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
     */
    protected string $module = 'Core';

    /**
     * Get the services provided by the provider.
     *
     * @var array<int, class-string>
     */
    protected array $providers = [
        RouteServiceProvider::class,
        EventServiceProvider::class,
        SeedServiceProvider::class,
    ];

    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected array $policies = [
        Announcement::class => AnnouncementPolicy::class,
    ];

    /**
     * Boot module observers.
     *
     * @var array<class-string, class-string>
     */
    protected array $observers = [
        Announcement::class => AnnouncementObserver::class,
    ];

    /**
     * register module aliases.
     *
     * @var array<string, class-string>
     */
    protected array $aliases = [
        'announcement' => Announcement::class,
    ];

    /**
     * The application's global middleware stack.
     *
     * @var array<int, class-string>
     */
    protected array $middleware = [
        AddXHeader::class,
        GzipEncodeResponse::class,
        CheckForDemoMode::class,
        LocaleMiddleware::class,
        IPFireWall::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string>>
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
     * @var array<string, class-string>
     */
    protected array $routeMiddleware = [
        'ip_whitelist' => IpWhitelist::class,
    ];

    /**
     * The available command shortname.
     *
     * @var array<int, class-string>
     */
    protected array $commands = [];

    /**
     * Bootstrap your package's services.
     *
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
     */
    private function getArguments(string $argumentString): array
    {
        return str_getcsv($argumentString, ',', "'");
    }

    /**
     * Checks if the current url matches the configured backend uri
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
