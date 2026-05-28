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

    'perfect_corp' => [
        'base_url' => env('PERFECT_CORP_API_BASE_URL'),
        'api_key' => env('PERFECT_CORP_API_KEY'),
        'bearer_key' => env('PERFECT_CORP_API_BEARER_KEY'),
        'enabled' => env('PERFECT_CORP_ENABLED', false),
        'demo_mode' => env('PERFECT_CORP_DEMO_MODE', true),
        'timeout_seconds' => env('PERFECT_CORP_TIMEOUT_SECONDS', 120),
        'poll_interval_seconds' => env('PERFECT_CORP_POLL_INTERVAL_SECONDS', 2),
        'max_attempts' => env('PERFECT_CORP_MAX_ATTEMPTS', 3),
    ],

];
