<?php

namespace App\Providers;

use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);

            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // when we go live we might want to force SSL
        // on the requests and responses
        if ($this->app->isProduction() && config('app.ssl')) {
            URL::forceScheme('https');
            URL::forceRootUrl(config('app.url'));
        }

        // prevent user to send accidental arrays
        if (! $this->app->isProduction()) {
            Mail::alwaysTo('foo@example.org');
        }

        // Handle SQL Error schema migrate
        if (PHP_OS === 'WINNT' && ! $this->app->isProduction()) {
            Schema::defaultStringLength(191);
        }

        // Log all SQL queries
        if (! $this->app->isProduction() && config('app.db_log')) {
            DB::listen(function ($query) {
                if ($query->time > 1000) {
                    Log::warning('An individual database query exceeded 1 second.', [
                        'sql' => $query->sql,
                    ]);
                }

                logger(Str::replaceArray('?', $query->bindings, $query->sql));
            });
        }

        // Remove 'data' from json api responses
        JsonResource::withoutWrapping();

        // Laravel’s safety mechanisms
        Model::preventAccessingMissingAttributes(! $this->app->isProduction());
        Model::preventSilentlyDiscardingAttributes(! $this->app->isProduction());

        // Find N+1 problems instantly by disabling lazy loading
        Model::preventLazyLoading(! $this->app->isProduction());

        // But in production, log the violation instead of throwing an exception.
        if ($this->app->isProduction()) {
            Model::handleLazyLoadingViolationUsing(function ($model, $relation) {
                $class = get_class($model);
                info("Attempted to lazy load [{$relation}] on model [{$class}].");
            });
        }

        // Log a warning if we spend more than a total of 2000ms querying.
        DB::whenQueryingForLongerThan(2000, function (Connection $connection) {
            Log::warning("Database queries exceeded 2 seconds on {$connection->getName()}");
        });

        if ($this->app->runningInConsole()) {
            // Log slow commands.
            $this->app[ConsoleKernel::class]->whenCommandLifecycleIsLongerThan(
                5000,
                function ($startedAt, $input, $status) {
                    Log::warning('A command took longer than 5 seconds.', [
                        'startedAt' => $startedAt,
                        'input' => $input,
                        'status' => $status,
                    ]);
                }
            );
        } else {
            // Log slow requests.
            $this->app[HttpKernel::class]->whenRequestLifecycleIsLongerThan(
                5000,
                function ($startedAt, $request, $response) {
                    Log::warning('A request took longer than 5 seconds.', [
                        'startedAt' => $startedAt,
                        'request' => $request,
                        // 'response' => $response,
                    ]);
                }
            );
        }
    }
}
