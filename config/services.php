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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'woocommerce' => [
        'api_token' => env('WOOCOMMERCE_API_TOKEN'),
        'webhook_secret' => env('WOOCOMMERCE_WEBHOOK_SECRET'),
        'store_url' => env('WOOCOMMERCE_STORE_URL'),
        'consumer_key' => env('WOOCOMMERCE_CONSUMER_KEY'),
        'consumer_secret' => env('WOOCOMMERCE_CONSUMER_SECRET'),
    ],

    /*
    |--------------------------------------------------------------------------
    | TraceNova (app usage tracking)
    |--------------------------------------------------------------------------
    | Set TRACENOVA_ENABLED=true and add the script URL or app ID from your
    | TraceNova app. Paste the full embed code in the partial if needed.
    */
    'tracenova' => [
        'enabled'   => env('TRACENOVA_ENABLED', false),
        'base_url'  => rtrim(env('TRACENOVA_BASE_URL', 'https://tracenova.sandbox.rw'), '/'),
        'app_id'    => env('TRACENOVA_APP_ID'),
        'api_key'   => env('TRACENOVA_API_KEY'), // optional: for Authorization header on session API
        'script_url' => env('TRACENOVA_SCRIPT_URL'), // optional: full URL to TraceNova tracker script
    ],

];