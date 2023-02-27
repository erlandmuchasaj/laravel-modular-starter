# Important
Add these packages once they have been updated to support laravel 10.
`"albertcht/invisible-recaptcha": "^1.9"`
`"arcanedev/log-viewer": "^9.0"`,


## SETUP
  - Add ``Modules\Core\Exceptions\GeneralException::class`` on ``app/Exception/Handler.php``
```php
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        GeneralException::class,
    ];
```

- Uncomment `BroadcastServiceProvider` in the array of providers in config/app.php,
we use it for Real time event notification

```php
        /**
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\BroadcastServiceProvider::class,  // Uncomment this line
        //
```

- Uncomment `AuthenticateSession` in the array of $middlewareGroups in app/Http/Kernel.php,
we use it for Session management

```php
    /**
     * The application's route middleware groups.
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class, // Uncomment this line
    ];
```

