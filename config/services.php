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

'jayapay' => [
    'merchant_code' => env('JAYAPAY_MERCHANT_CODE'),

    // DEPOSIT
    'create_order_url' => env('JAYAPAY_CREATE_ORDER_URL'),
    'notify_url' => env('JAYAPAY_NOTIFY_URL'),
    'expiry_period' => env('JAYAPAY_EXPIRY_PERIOD', 1440),

    // WITHDRAW
    'payout_url' => env('JAYAPAY_PAYOUT_URL'),
    'withdraw_notify_url' => env('JAYAPAY_WITHDRAW_NOTIFY_URL'),
    'withdraw_order_type' => env('JAYAPAY_WITHDRAW_ORDER_TYPE', '0'),

    // KEYS
    'private_key' => env('JAYAPAY_PRIVATE_KEY'),
    'platform_public_key' => env('JAYAPAY_PLATFORM_PUBLIC_KEY'),
],

];
