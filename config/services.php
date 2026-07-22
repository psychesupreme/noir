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

    'mpesa' => [
        'env'             => env('MPESA_ENV', env('MPESA_ENVIRONMENT', 'sandbox')),
        'environment'     => env('MPESA_ENV', env('MPESA_ENVIRONMENT', 'sandbox')),
        'key'             => env('MPESA_CONSUMER_KEY'),
        'consumer_key'    => env('MPESA_CONSUMER_KEY'),
        'secret'          => env('MPESA_CONSUMER_SECRET'),
        'consumer_secret' => env('MPESA_CONSUMER_SECRET'),
        'shortcode'       => env('MPESA_SHORTCODE', '174379'),
        'passkey'         => env('MPESA_PASSKEY'),
        'callback_url'    => env('MPESA_CALLBACK_URL', 'https://noir-bloom-erp.fly.dev/api/v1/mpesa/callback'),
        'validate_ip'     => env('MPESA_VALIDATE_IP', false),
        'allowed_ips'     => env('MPESA_ALLOWED_IPS', ''),
    ],

    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('APP_URL') . '/auth/social/google/callback',
    ],

    'africastalking' => [
        'username'  => env('AFRICASTALKING_USERNAME', 'sandbox'),
        'api_key'   => env('AFRICASTALKING_API_KEY'),
        'from'      => env('AFRICASTALKING_SENDER_ID', env('AFRICASTALKING_FROM', 'NOIRBLOOM')),
        'sender_id' => env('AFRICASTALKING_SENDER_ID', env('AFRICASTALKING_FROM', 'NOIRBLOOM')),
    ],

    'etims' => [
        'enabled'     => env('ETIMS_ENABLED', false),
        'env'         => env('ETIMS_ENV', env('ETIMS_ENVIRONMENT', 'sandbox')),
        'environment' => env('ETIMS_ENV', env('ETIMS_ENVIRONMENT', 'sandbox')),
        'serial'      => env('ETIMS_DEVICE_SERIAL', 'MOCK-ESD-001'),
        'pin'         => env('ETIMS_PIN', env('ETIMS_TAXPAYER_PIN', 'P000000000A')),
    ],

];
