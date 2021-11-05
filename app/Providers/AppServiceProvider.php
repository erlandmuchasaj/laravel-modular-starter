<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        # when we go live we might want to force SSL
        # on the requests and responses
        if (App::environment('production') && config('app.ssl')) {
            URL::forceScheme('https');
        }

        Schema::defaultStringLength(191);

        # Remove 'data' from json api responses
        JsonResource::withoutWrapping();

        # Find N+1 problems instantly by disabling lazy loading
        Model::preventLazyLoading($this->app->isLocal());
    }
}
