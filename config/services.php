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

    'turnstile' => [
        'site_key'   => env('TURNSTILE_SITE_KEY'),
        'secret_key' => env('TURNSTILE_SECRET_KEY'),
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

/*
| Payment gateway "BankPay" — dipakai untuk deposit (collection) sekaligus
| withdraw (payment on behalf / pembayaran atas nama).
|
| Semua request memakai POST form-urlencoded dan ditandatangani MD5 uppercase
| dengan aturan: parameter tidak kosong diurutkan ASCII, digabung
| key1=value1&key2=value2, lalu ditambah &key=<secret> sebelum di-hash.
*/
'bankpay' => [
    'base_url' => env('BANKPAY_BASE_URL', 'https://pay.bankpay.cfd'),

    // ID pedagang + kunci rahasia dari dashboard BankPay.
    'member_id' => env('BANKPAY_MEMBER_ID'),
    'key' => env('BANKPAY_KEY'),

    'currency' => env('BANKPAY_CURRENCY', 'IDR'),

    // Kode jenis pembayaran (lihat "Jenis pembayaran yang tersedia saat ini").
    'bank_code' => env('BANKPAY_BANK_CODE', 'bank'),

    // Invoice deposit kedaluwarsa (menit) — dipakai untuk expired_at lokal.
    'expiry_minutes' => (int) env('BANKPAY_EXPIRY_MINUTES', 60),

    /*
    | Berapa lama setelah invoice kedaluwarsa statusnya masih ditanyakan ke
    | gateway. Ini yang mencegah invoice mati ditanyai selamanya: tanpa batas
    | ini, satu invoice yang ditinggalkan user akan terus di-poll sampai
    | jendela retensi habis — ribuan panggilan API untuk sesuatu yang tidak
    | akan pernah dibayar, dan risiko kena pembatasan laju dari gateway.
    |
    | Tetap diberi kelonggaran (bukan nol) karena pembayaran yang masuk
    | beberapa menit setelah lewat waktu tetap harus diakui.
    */
    'poll_grace_minutes' => (int) env('BANKPAY_POLL_GRACE_MINUTES', 30),

    // Kosongkan untuk memakai route() bawaan aplikasi. Isi hanya kalau URL
    // publik berbeda dari APP_URL (mis. di balik WAF / domain terpisah).
    'deposit_notify_url' => env('BANKPAY_DEPOSIT_NOTIFY_URL'),
    'deposit_return_url' => env('BANKPAY_DEPOSIT_RETURN_URL'),
    'payout_notify_url' => env('BANKPAY_PAYOUT_NOTIFY_URL'),
],

];
