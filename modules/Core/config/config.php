<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Current module name
    |--------------------------------------------------------------------------
    */
    'name' => 'Core',

    /*
    |--------------------------------------------------------------------------
    | Check if asgard was installed
    |--------------------------------------------------------------------------
    */
    'is_installed' => env('INSTALLED', false),

    /*
    |--------------------------------------------------------------------------
    | These are the core modules that should NOT be disabled under any circumstance
    |--------------------------------------------------------------------------
    */
    'CoreModules' => [
        'core',
        'user',
    ],

    /*
    |--------------------------------------------------------------------------
    | The prefix that'll be used for the administration and routes
    |--------------------------------------------------------------------------
    */
    'web_prefix' => '',
    'web_group' => '',

    'api_prefix' => 'api',
    'api_group' => 'api.', // we need the trailing . to form the route names like api.auth.login

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    | You can customise the Middleware that should be loaded.
    | The localizationRedirect middleware is automatically
    | loaded for both Backend and Frontend routes.
    | Note that this middleware will be
    | assigned on all routes.
    */
    'middleware' => [
        'back' => [
            'admin',
        ],
        'web' => [
            'web',
        ],
        'api' => [
            'api',
        ],
    ],
];
