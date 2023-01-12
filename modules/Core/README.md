**CORE module**

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


## Modules

Here we will describe the whole process of Cloning adding new modules to the system without,
effecting the other part of the system.

## Creating new module

Here everything is done inside a Module. 
Basically one module is another Laravel application with all its components, views, 
routes, middleware, events, database, seeds and much, much more. 
You will fill right at home.


`php artisan module:make <module-name>`

  - First Duplicate the Example folder from  ``` /modules/Example ``` and rename it to the ```/modules/CustomModule``` you are building.
  - The name should follow PSR4 naming conventions (CamelCase).
  - Then Go inside the newly created module and rename *Example* to *CustomModule* accordingly.
  - Update module *composer.json* file to correspond to the newly created module.
  - *NOTE* Make sure to also update the *Namespaces* accordingly.
  - Update the main composer.json of the application with the newly created module.

@TODO: I am working to create some commands to avoid any Typos and for easy access on all module functionalities.

```json
{
    "require": {
        "modules/custom_module": "1.0.0"
    }
}
```
- Also add the module to the repository in composer.json
```json
{
  "repositories": [
    {
      "type": "path",
      "url": "./modules/CustomModule"
    }
  ]
}
```

@TODO: I am working on providing an autoconfiguration for all modules,

```json
{
  "repositories": [
    {
      "type": "path",
      "url": ".\/modules\/*"
    }
  ]
}
 ```

  - The package is *auto-discovered* so there is no need to add it to *app/config.php* providers list.
  - Then run ````  composer update  ```` and you are good to go!

To add a table to specific module use command:
``php artisan make:migration create_modules_table --path=modules/CustomModule/database/migrations``

- Good Luck

## Code of Conduct

In order to ensure that the Task-V5 is properly coded, 
please review and abide by the [Code of Conduct](https://cloud.draft2017.com/index.php/s/BTQiKmgMTPDTAtg) and [PHP Style Guide](https://cloud.draft2017.com/index.php/s/WB7TrcaSZJPTgKz).

## Security Vulnerabilities

If you discover a security vulnerability within The Starter project, please send an e-mail to Erland Muchasaj via [erland.muchasaj@gmail.com](mailto:erland.muchasaj@gmail.com).
All security vulnerabilities will be promptly addressed.

## Packages we might use

  - https://github.com/cviebrock/eloquent-sluggable
  - https://github.com/antonioribeiro/countries-laravel
  - https://github.com/jpmurray/awesome-spark#code-snippets
  - https://gist.github.com/dillinghamio/7f3b776e0ff1007cc877d63d6aaee10d

## Performance TIPS

- Cursor, Chunks, LazyCollections, sole(), $route->missing()
```php
    # NO
    public function countTeamMembers()
    {
        return $this->teamMembers->count();
    }
    
    # YES
    public function countTeamMembers()
    {
        return $this->teamMembers()->count();
    }
```

## 5 Tips for the Laravel Service Container

  - Avoid executing DB queries in service providers
  - Avoid reading session data in service providers
  - Avoid resolving bindings in the register method
  - Avoid reading sessions on Repositories or Services.
  - Scoped instances vs. Singletons

```php
# Instead of this
$this->app->singleton(Transistor::class, function ($app) {
    return new Transistor($app->make(PodcastParser::class));
});

# Do this 
$this->app->scoped(Transistor::class, function ($app) {
    return new Transistor($app->make(PodcastParser::class));
}); 

```

 - Use rebinding events to refresh dependencies
```php
    $this->app->singleton(Service::class, function ($app) {
        $service = new Service();
        $service->setTenant($app['tenant'])
        
        # code
        $app->refresh('tenant', $service, 'setTenant'); // <==
        
        return $service;
    });
```


## NOTES
1. Always use named routes
2. Try to avoid closure
3. All API endpoint are prefixed with api.
4. All API endpoints are namespaced under /Api
5. Use PHP stan to test your code 
6. Use **GET/POST/PUT/PATCH/DELETE** for HTTP calls.
   1. **GET** to retrieve data from backend should return 200
   2. **POST** to send data to backend aka create new entity should return 201
   3. **PUT** to update an entity to backend should return 200
   4. **PATCH** to update a field on an entity on backend, should return 200
   5. **DELETE** to remove an entity from DB and should return 204
7. Use ``use Symfony\Component\HttpFoundation\Response`` for HTTP status codes
   1. example: `Response::HTTP_OK` or `HTTP_CREATED` or `HTTP_ACCEPTED` or `HTTP_NO_CONTENT` or `HTTP_NO_CONTENT` etc.
   2. Most commonly use status codes: 200, 201, 202, 203, 204, 208, 301, 302, 303, 307, 308, 400, 401, 402, 403, 404, 405, 408, 413, 415, 422, 429,
8. All related packages should publish the config inside the corresponding module they belong to.

```
./vendor/bin/phpstan analyse --memory-limit=2G
```

```
./vendor/bin/pint # this is a  PHP CS fixer.
```

To add some exclusion to specific use cases:
```text
# - '#Access to an undefined property Modules\*\Http\Requests\(.*)::$.*#'
# - '#Access to an undefined property Modules\*\Models(.*)::$.*#'
# - '#Call to an undefined method Modules\*\Models(.*)::.*#'
# - '#Call to an undefined static method Modules\(.*)\Models(.*)::.*#'
# - '#Call to an undefined method Illuminate\(.*)::.*#'
# - '#Call to an undefined static method Illuminate\(.*)::.*#'
# - '#Access to an undefined property Modules\*\Http\Requests\(.*)::$.*#'
```

```php
// null coalesce if you need a default value.
return $user->profile->twitter_id ?? null;

// optional
return optional($user->profile)->twitter_id;
```

If you want a default value, null coalesce is a better choice:

```php
    return $user->profile->nickname ?? randomNickname();
```

## @TODO
- Add a Cache Layer when reading data.
