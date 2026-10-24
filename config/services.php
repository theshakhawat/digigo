<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
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

    'dgepay' => [
        'client_id' => env('DGEPAY_CLIENT_ID', '6dfa6e6e66d040b68648afaa6a95979e'),
        'client_secret' => env('DGEPAY_CLIENT_SECRET', 'b6dc62fbfe887c52'),
        'client_api_key' => env('DGEPAY_CLIENT_API_KEY', '8d89041569146d8b15b1c39b79f5070'),
        'base_url' => env('DGEPAY_BASE_URL', 'https://api-uat.dgepay.net/dipon/v3'),
    ],

];
