<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    'version' => env('APP_VERSION', 'Laravel '.app()->version()),

    'slug' => 'laravel-starter',

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    'debug_emails' => env('APP_DEBUG_EMAILS', false),

    'db_log' => env('DB_LOG', false),

    'sentry_support' => env('SENTRY_SUPPORT', false),

    'debug_blacklist' => [
        '_ENV' => [
            'APP_KEY',
            'DB_DATABASE',
            'DB_USERNAME',
            'DB_PASSWORD',
        ],

        '_SERVER' => [
            'APP_KEY',
            'DB_DATABASE',
            'DB_USERNAME',
            'DB_PASSWORD',
        ],

        '_POST' => [
            'password',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Domain Routing
    |--------------------------------------------------------------------------
    |
    | You can use the admin panel on a separate subdomain.
    |
    | Example: 'example.com'
    |
    */

    'domain' => env('APP_DOMAIN', null),

    /*
    |--------------------------------------------------------------------------
    | SSL Site
    |--------------------------------------------------------------------------
    |
    |  If the application uses SSL
    |
    */

    'ssl' => env('APP_SSL', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL', null),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'UTC',

    'time_format' => env('APP_TIME_FORMAT', 'Y-m-d H:i:s'),

    'time_format_js' => env('APP_TIME_FORMAT_JS', 'yy-mm-dd'),

    /*
    |--------------------------------------------------------------------------
    | Application Allow multi localisation
    |--------------------------------------------------------------------------
    |
    | The application allow multi localisation determines if the platform
    | support multilingual sites and translation
    |
    */

    'locale_status' => env('LOCALE_STATUS', false),

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Supported locales
    |--------------------------------------------------------------------------
    |
    | A list of locales the public site supports. Used to determine valid
    | localized urls and to generate form fields in multiple languages in Laravel Starter
    |
    | Add your language code to this array.
    | The code must have the same name as the language folder.
    | Be sure to add the new language in alphabetical order.
    |
    | The language picker will not be available if there is only one language option
    | Commenting out languages will make them unavailable to the user
    |
    | @var array.
    |
    */

    'locales' => [
        // 'ar' => ['name' => 'Arabic', 'script' => 'Arab', 'native' => 'العربية', 'iso' => 'ar', 'code' => 'ar-AE', 'rtl' => true],
        // 'az' => ['name' => 'Azerbaijani (Latin)', 'script' => 'Latn', 'native' => 'azərbaycanca', 'iso' => 'az', 'code' => 'az-AZ', 'rtl' => false],
        // 'da' => ['name' => 'Danish', 'script' => 'Latn', 'native' => 'dansk', 'iso' => 'da', 'code' => 'da-DK', 'rtl' => false],
        // 'de' => ['name' => 'German', 'script' => 'Latn', 'native' => 'Deutsch', 'iso' => 'de', 'code' => 'de-DE', 'rtl' => false],
        // 'el' => ['name' => 'Greek', 'script' => 'Grek', 'native' => 'Ελληνικά', 'iso' => 'el', 'code' => 'el-GR', 'rtl' => false],
        // 'en' => ['name' => 'English', 'script' => 'Latn', 'native' => 'English', 'iso' => 'en', 'code' => 'en-US', 'rtl' => false],
        // 'es' => ['name' => 'Spanish', 'script' => 'Latn', 'native' => 'Español', 'iso' => 'es', 'code' => 'es-ES', 'rtl' => false],
        // 'fa' => ['name' => 'Persian', 'script' => 'Arab', 'native' => 'فارسی', 'iso' => 'fa', 'code' => 'fa-IR', 'rtl' => true], # Farsi
        // 'fr' => ['name' => 'French', 'script' => 'Latn', 'native' => 'Français', 'iso' => 'fr', 'code' => 'fr-FR', 'rtl' => false],
        // 'he' => ['name' => 'Hebrew', 'script' => 'Hebr', 'native' => 'עברית', 'iso' => 'he', 'code' => 'he-IL', 'rtl' => true],
        // 'hu' => ['name' => 'Hungarian', 'script' => 'Latn', 'native' => 'magyar', 'iso' => 'hu', 'code' => 'hu-HU', 'rtl' => false],
        // 'id' => ['name' => 'Indonesian', 'script' => 'Latn', 'native' => 'Bahasa Indonesia', 'iso' => 'id', 'code' => 'id-ID', 'rtl' => false],
        // 'it' => ['name' => 'Italian', 'script' => 'Latn', 'native' => 'italiano', 'iso' => 'it', 'code' => 'it-IT', 'rtl' => false],
        // 'ja' => ['name' => 'Japanese', 'script' => 'Jpan', 'native' => '日本語', 'iso' => 'ja', 'code' => 'ja-JP', 'rtl' => false],
        // 'nl' => ['name' => 'Dutch', 'script' => 'Latn', 'native' => 'Nederlands', 'iso' => 'nl', 'code' => 'nl-NL', 'rtl' => false],
        // 'nb' => ['name' => 'Norwegian Bokmål', 'script' => 'Latn', 'native' => 'Bokmål', 'iso' => 'nb', 'code' => 'nb-NO', 'rtl' => false],
        // 'pt' => ['name' => 'Portuguese', 'script' => 'Latn', 'native' => 'português', 'iso' => 'pt', 'code' => 'pt-BR', 'rtl' => false],
        // 'ru' => ['name' => 'Russian', 'script' => 'Cyrl', 'native' => 'русский', 'iso' => 'ru', 'code' => 'ru-RU', 'rtl' => false],
        // 'sq' => ['name' => 'Albanian', 'script' => 'Latn', 'native' => 'shqip', 'iso' => 'sq', 'code' => 'sq-AL', 'rtl' => false],
        // 'sv' => ['name' => 'Swedish', 'script' => 'Latn', 'native' => 'svenska', 'iso' => 'sv', 'code' => 'sv-SE', 'rtl' => false],
        // 'sl' => ['name' => 'Slovene', 'script' => 'Latn', 'native' => 'slovenščina', 'iso' => 'sl', 'code' => 'sl-SI', 'rtl' => false],
        // 'th' => ['name' => 'Thai', 'script' => 'Thai', 'native' => 'ไทย', 'iso' => 'th', 'code' => 'th-TH', 'rtl' => false],
        // 'tr' => ['name' => 'Turkish', 'script' => 'Latn', 'native' => 'Türkçe', 'iso' => 'tr', 'code' => 'tr-TR', 'rtl' => false],
        // 'uk' => ['name' => 'Ukrainian', 'script' => 'Cyrl', 'native' => 'українська', 'iso' => 'uk', 'code' => 'uk-UA', 'rtl' => false],
        // 'zh' => ['name' => 'Chinese (Simplified)', 'script' => 'Hans', 'native' => '简体中文', 'iso' => 'zh', 'code' => 'zh-CN', 'rtl' => false],

        /*
         * Key is the Laravel locale code
         *
         * Index 0 of sub-array is the Carbon locale code
         * Index 1 of sub-array is the PHP locale code for setlocale()
         * Index 2 of sub-array is whether or not to use RTL (right-to-left) html direction for this language
         */
        'ar' => ['ar', 'ar-AR', true],
        'az' => ['az', 'az-AZ', false],
        'da' => ['da', 'da-DK', false],
        'de' => ['de', 'de-DE', false],
        'el' => ['el', 'el-GR', false],
        'en' => ['en', 'en-US', false],
        'es' => ['es', 'es-ES', false],
        'fa' => ['fa', 'fa-IR', true],
        'fr' => ['fr', 'fr-FR', false],
        'he' => ['he', 'he-IL', true],
        'hu' => ['hu', 'hu-HU', false],
        'id' => ['id', 'id-ID', false],
        'it' => ['it', 'it-IT', false],
        'ja' => ['ja', 'ja-JP', false],
        'nl' => ['nl', 'nl-NL', false],
        'nb' => ['nb', 'nb-NO', false],
        'pt' => ['pt', 'pt-BR', false],
        'ru' => ['ru', 'ru-RU', false],
        'sv' => ['sv', 'sv-SE', false],
        'sl' => ['sl', 'sl-SI', false],
        'th' => ['th', 'th-TH', false],
        'tr' => ['tr', 'tr-TR', false],
        'uk' => ['uk', 'uk-UA', false],
        'zh' => ['zh', 'zh-CN', false],
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used by the Faker PHP library when generating fake
    | data for your database seeds. For example, this will be used to get
    | localized telephone numbers, street address information and more.
    |
    */

    'faker_locale' => 'en_US',

    /*
    |--------------------------------------------------------------------------
    | PHP Locale Code
    |--------------------------------------------------------------------------
    |
    | The PHP locale determines the default locale that will be used
    | by the Carbon library when setting Carbon's localization.
    |
    */

    'locale_php' => env('APP_LOCALE_PHP', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',


    /*
    |--------------------------------------------------------------------------
    | Default Country
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default country by country code.
    | Ensure it is uppercase and reflects the 'code' column of the
    | countries table.
    |
    */

    'default_country' => null,

    /*
    |--------------------------------------------------------------------------
    | Base Currency Code
    |--------------------------------------------------------------------------
    |
    | Here you may specify the base currency code for your application.
    |
    */

    'currency' => env('APP_CURRENCY', 'EUR'),

    /*
    |--------------------------------------------------------------------------
    | Platform Author
    |--------------------------------------------------------------------------
    |
    | The name of mastermind behind
    | the application
    |
    */

    'author' => env('APP_AUTHOR', 'Erland Muchasaj'),

    /*
    |--------------------------------------------------------------------------
    | Application Demo Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in demo mode, all request to the front site
    | are redirecting. To view the full front site a user must
    | first visit /demo.
    |
    */

    'demo' => env('APP_DEMO', false),

    /*
    |--------------------------------------------------------------------------
    | Application Testing Mode
    |--------------------------------------------------------------------------
    |
    | When your application is currently running tests
    |
    */

    'testing' => env('APP_TESTING', false),

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Arr' => Illuminate\Support\Arr::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'Date' => Illuminate\Support\Facades\Date::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Http' => Illuminate\Support\Facades\Http::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'RateLimiter' => Illuminate\Support\Facades\RateLimiter::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        // 'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'Str' => Illuminate\Support\Str::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,

    ],

];
