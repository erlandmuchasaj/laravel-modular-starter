**CORE module**

## Modules

Here we will describe the whole process of Cloning adding new modules to the system without,
effecting the other part of the system.

## Creating new module

Here everything is done inside a Module. 
Basically one module is another Laravel application with all its components, views, 
routes, middleware, events, database, seeds and much, much more. 
You will fill right at home.


`php artisan module:make <module-name>`

- First Duplicate the Example folder from  ``` /modules/Example ``` and rename it to the */modules/CustomModule* you are building.
  The name should follow PSR4 naming conventions (CamelCase).
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
- Then run ```` composer update ```` and you are good to go!


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
https://github.com/cviebrock/eloquent-sluggable
https://github.com/antonioribeiro/countries-laravel
https://github.com/jpmurray/awesome-spark#code-snippets
https://gist.github.com/dillinghamio/7f3b776e0ff1007cc877d63d6aaee10d


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


## NOTES:
1. Always use named routes
2. Try to avoid closure
3. All API endpoint are prefixed with api.
4. All API endpoints are namespaced under /Api
5. Use PHP stan to test your code 
```
./vendor/bin/phpstan analyse --memory-limit=2G
```



```php
// null coalesce
return $user->profile->twitter_id ?? null;
// optional
return optional($user->profile)->twitter_id;
```

If you want a default value, null coalesce is a better choice:

```php
    return $user->profile->nickname ?? randomNickname();
```

#@TODO:
- Add a Cache Layer when reading data.
