<?php

use Illuminate\Support\Facades\Facade;

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

    'slug' => \Modules\Core\Utils\EmCms::NAME,

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

    'timezone' => env('APP_TIMEZONE', 'UTC'),

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

    'locale' => env('APP_LOCALE', 'en'),

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
        'en' => ['name' => 'English', 'script' => 'Latn', 'native' => 'English', 'iso' => 'en', 'code' => 'en-US', 'rtl' => false],
        // 'es' => ['name' => 'Spanish', 'script' => 'Latn', 'native' => 'Español', 'iso' => 'es', 'code' => 'es-ES', 'rtl' => false],
        // 'fa' => ['name' => 'Persian', 'script' => 'Arab', 'native' => 'فارسی', 'iso' => 'fa', 'code' => 'fa-IR', 'rtl' => true], # Farsi
        // 'fr' => ['name' => 'French', 'script' => 'Latn', 'native' => 'Français', 'iso' => 'fr', 'code' => 'fr-FR', 'rtl' => false],
        // 'he' => ['name' => 'Hebrew', 'script' => 'Hebr', 'native' => 'עברית', 'iso' => 'he', 'code' => 'he-IL', 'rtl' => true],
        // 'hu' => ['name' => 'Hungarian', 'script' => 'Latn', 'native' => 'magyar', 'iso' => 'hu', 'code' => 'hu-HU', 'rtl' => false],
        // 'id' => ['name' => 'Indonesian', 'script' => 'Latn', 'native' => 'Bahasa Indonesia', 'iso' => 'id', 'code' => 'id-ID', 'rtl' => false],
        'it' => ['name' => 'Italian', 'script' => 'Latn', 'native' => 'italiano', 'iso' => 'it', 'code' => 'it-IT', 'rtl' => false],
        // 'ja' => ['name' => 'Japanese', 'script' => 'Jpan', 'native' => '日本語', 'iso' => 'ja', 'code' => 'ja-JP', 'rtl' => false],
        // 'nl' => ['name' => 'Dutch', 'script' => 'Latn', 'native' => 'Nederlands', 'iso' => 'nl', 'code' => 'nl-NL', 'rtl' => false],
        // 'nb' => ['name' => 'Norwegian Bokmål', 'script' => 'Latn', 'native' => 'Bokmål', 'iso' => 'nb', 'code' => 'nb-NO', 'rtl' => false],
        // 'pt' => ['name' => 'Portuguese', 'script' => 'Latn', 'native' => 'português', 'iso' => 'pt', 'code' => 'pt-BR', 'rtl' => false],
        // 'ru' => ['name' => 'Russian', 'script' => 'Cyrl', 'native' => 'русский', 'iso' => 'ru', 'code' => 'ru-RU', 'rtl' => false],
        'sq' => ['name' => 'Albanian', 'script' => 'Latn', 'native' => 'shqip', 'iso' => 'sq', 'code' => 'sq-AL', 'rtl' => false],
        // 'sv' => ['name' => 'Swedish', 'script' => 'Latn', 'native' => 'svenska', 'iso' => 'sv', 'code' => 'sv-SE', 'rtl' => false],
        // 'sl' => ['name' => 'Slovene', 'script' => 'Latn', 'native' => 'slovenščina', 'iso' => 'sl', 'code' => 'sl-SI', 'rtl' => false],
        // 'th' => ['name' => 'Thai', 'script' => 'Thai', 'native' => 'ไทย', 'iso' => 'th', 'code' => 'th-TH', 'rtl' => false],
        // 'tr' => ['name' => 'Turkish', 'script' => 'Latn', 'native' => 'Türkçe', 'iso' => 'tr', 'code' => 'tr-TR', 'rtl' => false],
        // 'uk' => ['name' => 'Ukrainian', 'script' => 'Cyrl', 'native' => 'українська', 'iso' => 'uk', 'code' => 'uk-UA', 'rtl' => false],
        // 'zh' => ['name' => 'Chinese (Simplified)', 'script' => 'Hans', 'native' => '简体中文', 'iso' => 'zh', 'code' => 'zh-CN', 'rtl' => false],
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
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => 'file',
        // 'store'  => 'redis',
    ],

    /*
    |--------------------------------------------------------------------------
    | Other SEO data ti be used
    |--------------------------------------------------------------------------
    |
    | Chose the direction of the css and typing
    */
    'title_name' => 'EMCMS Starter',
    'seo_title' => 'EMCMS Starter',
    'seo_description' => 'EMCMS Starter',
    'seo_image' => '/img/seo_img.png',
    // not usefully anymore
    'seo_author' => 'Erland Muchasaj - Software dveloper',
    'seo_keyword' => 'Software, development, IT, programming.',
    'seo_copyright' => '@ErlandMuchasaj',

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
     | Whether announcements are enabled or not.
     */

    'announcements' => env('APP_ANNOUNCEMENTS', true),

    /*
    | Whether registration is enabled
    | or verification is enabled
    | or Reset is enabled
    */

    'login' => env('ENABLE_LOGIN', true),
    'register' => env('ENABLE_REGISTRATION', true),
    'reset' => env('ENABLE_RESET', true),
    'verify' => env('ENABLE_VERIFICATION', true),
    'confirm' => env('ENABLE_CONFIRM', true),
    'remember_me' => env('ENABLE_REMEMBER_ME', true),

    /*
    |--------------------------------------------------------------------------
    | User single sign in token
    |--------------------------------------------------------------------------
    |
    | When active, a user can only have one session active at a time
    | That is all other sessions for that user will be deleted when they log in
    | (They can only be logged into one place at a time, all others will be logged out)
    | AuthenticateSession middleware must be enabled
    |
    */

    'single_login' => env('SINGLE_LOGIN', false),

    'social_login' => env('SOCIAL_LOGIN', true),

    'social_providers' => [
        'bitbucket',
        'facebook',
        'google',
        'github',
        'linkedin',
        'twitter',
    ],

    'disable_autologin' => env('DISABLE_AUTOLOGIN', false),

    'retype_password_when_deleting' => env('RETYPE_PASSWORD_WHEN_DELETING', true),

    /*
    |--------------------------------------------------------------------------
    | Socialite session variable name
    |--------------------------------------------------------------------------
    |
    | Contains the name of the currently logged in provider in the users session
    | Makes it so social logins can not change passwords, etc.
    */

    'socialite_session_name' => 'socialite_provider',

    /*
     | Whether a user can change their email address after
     | their account has already been created
     */

    'change_email' => env('CHANGE_EMAIL', true),

    /*
     | Whether the user has to confirm their email when signing up
     | and also when they change they email
     */

    'confirm_email' => env('CONFIRM_EMAIL', true),

    /*
     | Whether a user can Deactivate / Remove
     | his/her account
     | NOTE: all accounts are softly deleted.
     */

    'deactivate_account' => env('DEACTIVATE_ACCOUNT', true),

    /*
     | Whether admins need 2FA enabled to visit the backend
     | Weather the 2FA is enabled or not site-wide
     */

    'admin_requires_2fa' => env('ADMIN_REQUIRES_2FA', true),
    '2fa_status' => env('2FA_STATUS', true),

    /*
    |--------------------------------------------------------------------------
    | Captcha configuration
    |--------------------------------------------------------------------------
    |
    | Get your credentials at: https://www.google.com/recaptcha/admin
    */
    'captcha' => [
        'registration' => env('REGISTRATION_CAPTCHA_STATUS', false),
        'login' => env('LOGIN_CAPTCHA_STATUS', false),
    ],

    /*
     | When creating users from the backend, only allow the assigning of roles and not individual permissions
     */

    'only_roles' => false,

    'default_role' => 'user',

    'roles' => [
        'superadmin' => 'superadmin',
        'admin' => 'admin',
        'user' => 'user',
    ],

    'permissions' => [
    ],

    /*
     | How many days before users have to change their passwords
     | false is off
     */

    'password_expires_days' => env('PASSWORD_EXPIRES_DAYS', 180),

    /*
    | Whether new users need to be approved by an administrator before logging in
    | If this is set to true, then confirm_email is not in effect
    | alias of [ requires_approval - requires_verification]
    */

    'requires_approval' => env('REQUIRES_APPROVAL', false),

    /*
    | Whether impersonate functionality is enabled
    */

    'impersonate' => env('ENABLE_IMPERSONATION', true),

    /*
    | Indicates if user avatar is required
    | Default size of the avatar if none is supplied
    */
    'avatar' => [
        'required' => env('REQUIRED_AVATAR', false),
        'size' => 80,
    ],

    /*
    |--------------------------------------------------------------------------
    | Login History
    |--------------------------------------------------------------------------
    |
    |  IF we want to keep trac of all user login history
    |
    */

    'last_ip' => env('LOG_LAST_IP', false),

    'login_history' => env('LOGIN_HISTORY', true),

    /*
    |--------------------------------------------------------------------------
    | Password History
    |--------------------------------------------------------------------------
    |
    | The number of most recent previous passwords to check against when changing/resetting a password
    | false is off which doesn't log password changes or check against them
    |
    */

    'password_history' => env('PASSWORD_HISTORY', 3),
    'password_history_limit_check' => env('PASSWORD_HISTORY_LIMIT_CHECK', 3),

    /*
    |--------------------------------------------------------------------------
    | Google Analytics
    |--------------------------------------------------------------------------
    |
    | Found in views/includes/partials/ga.blade.php
    */

    'google_analytics' => env('GOOGLE_ANALYTICS', 'UA-XXXXX-X'),
    'google_tagmanager' => env('GOOGLE_TAGMANAGER', 'GTM-XXXXXXX'),

    /*
    |--------------------------------------------------------------------------
    | Idempotency
    |--------------------------------------------------------------------------
    |
    | In the context of REST APIs,
    | when making multiple identical requests has the same effect
    | as making a single request – then that REST API is called idempotent..
    |
    */

    'idempotency' => [
        'key' => env('IDEMPOTENT_KEY', 'Idempotency-Key'),
        'cache_time' => 60,
        'methods' => [
            'POST', // POST is NOT idempotent.
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | File manager user limit
    |--------------------------------------------------------------------------
    |
    | Specify in -MB- how many data a user can upload
    */

    'file_limit' => 16,

    /*
    |--------------------------------------------------------------------------
    | Activate or deactivate packages on the fly
    |--------------------------------------------------------------------------
    |
    */

    'package' => [
        'log-viewer' => true,
        'telescope' => true,
        'horizon' => false,
        'activitylog' => true,
        'socialite' => true,
        'laravel-impersonate' => false,
        'geoip' => false,
    ],

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
        App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\TelescopeServiceProvider::class,
        // App\Providers\FortifyServiceProvider::class, # uncomment this if install fortify

    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded, so they don't hinder performance.
    |
    */

    'aliases' => Facade::defaultAliases()->merge([
        // ...
        'Agent' => Jenssegers\Agent\Facades\Agent::class,
    ])->toArray(),

];
