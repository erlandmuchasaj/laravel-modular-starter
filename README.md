# [About ME](https://erlandmuchasaj.tech)

[Laravel](https://laravel.com/) enthusiast and [PHP](https://www.php.net/) Guru.
I believe development must be an enjoyable and creative experience to be truly fulfilling.

---

# About Laravel modular starter
Laravel modular starter for any project, Big or Small. 

It offers autoload of all modules and auto binding for most of its components. 
If you like Laravel, you will love **Laravel Modular starter**, and you will feel right at home. 

It follows the same folder structure as a Laravel project, the only difference is instead of app, you have src, the 
rest is same.

## Creating new module

Here everything is done inside a Module.
Basically one module is a laravel within another Laravel application with all its components, views,
routes, middleware, events, database, seeders, factories and much, much more.

```bash
php artisan module:make <ModuleName>
```

- The module is *auto-discovered* so there is no need to add it to *app/config.php* providers list.
- Then run ``` composer update ``` and you are good to go!

---

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please see [SECURITY](SECURITY.md) for details.

## Credits

- [Erland Muchasaj](https://github.com/erlandmuchasaj)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
