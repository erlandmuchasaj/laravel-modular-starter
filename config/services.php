<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_CALLBACK_URL'),
        'enabled' => env('GOOGLE_ENABLED', false),
        'scopes' => [],
        'with' => [],
        'fields' => [],
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_CALLBACK_URL'),
        'enabled' => env('FACEBOOK_ENABLED', false),
        'scopes' => [],
        'with' => [],
        'fields' => [],
    ],

    'twitter' => [
        'client_id' => env('TWITTER_CLIENT_ID'),
        'client_secret' => env('TWITTER_CLIENT_SECRET'),
        'redirect' => env('TWITTER_CALLBACK_URL'),
        'enabled' => env('TWITTER_ENABLED', false),
        'scopes' => [],
        'with' => [],
        'fields' => [],
    ],

    'linkedin' => [
        'client_id' => env('LINKEDIN_CLIENT_ID'),
        'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
        'redirect' => env('LINKEDIN_CALLBACK_URL'),
        'enabled' => env('LINKEDIN_ENABLED', false),
        'scopes' => [],
        'with' => [],
        'fields' => [],
    ],

    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'), // Your GitHub Client ID
        'client_secret' => env('GITHUB_CLIENT_SECRET'), // Your GitHub Client Secret
        'redirect' => env('GITHUB_CALLBACK_URL'), // Github callback url
        'enabled' => env('GITHUB_ENABLED', false), // if it is enabled
        'scopes' => ['read:user'],
        'with' => [],
        'fields' => [],
    ],

    'recaptcha' => [
        'client_id' => env('INVISIBLE_RECAPTCHA_SITEKEY'),
        'client_secret' => env('INVISIBLE_RECAPTCHA_SECRETKEY'),
        'enabled' => env('INVISIBLE_RECAPTCHA_ENABLED', false),
        'badge_hide' => env('INVISIBLE_RECAPTCHA_BADGEHIDE', false),
        'debug' => env('INVISIBLE_RECAPTCHA_DEBUG', false),
        'timeout' => env('INVISIBLE_RECAPTCHA_TIMEOUT', 5),
        'position' => env('INVISIBLE_RECAPTCHA_DATABADGE', 'bottomright'),
    ],

    'ip' => [
        'key' => env('IPAPI_KEY'),
        'enabled' => env('IPAPI_ENABLED', false),
    ],

    'pwned' => [
        'key' => env('PWNED_KEY'),
        'enabled' => env('PWNED_ENABLED', false),
    ],
    'indisposable' => [
        'default' => env('INDISPOSABLE_SERVICE', 'open.kickbox'),
        'enabled' => env('INDISPOSABLE_ENABLED', false),
        'connections' => [
            'open.kickbox' => [
                'domain' => 'https://open.kickbox.com/v1/disposable',
                'email' => 'https://open.kickbox.com/v1/disposable', # this does not support email check
            ],

            'validator.pizza' => [
                'domain' => 'https://www.validator.pizza/domain',
                'email' => 'https://www.validator.pizza/email',
            ],

            'block-temporary-email' => [
                'domain' => 'https://block-temporary-email.com/check/domain',
                'email' => 'https://block-temporary-email.com/check/email',
            ],
        ]
    ],
];
