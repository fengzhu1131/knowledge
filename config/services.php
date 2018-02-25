<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'weixin' => [
        'app_id' => env('WEIXIN_APP_ID'),
        'app_secret' => env('WEIXIN_APP_SECRET'),
        'uri' => env('WEIXIN_URI','https://api.weixin.qq.com'),
        'url' => env('WEIXIN_URL','/cgi-bin'),
    ],
    'weixin1' => [
        'app_id' => env('WEIXIN_APP_ID1'),
        'app_secret' => env('WEIXIN_APP_SECRET1'),
        'uri' => env('WEIXIN_URI','https://api.weixin.qq.com'),
        'url' => env('WEIXIN_URL','/cgi-bin'),
    ],
    "osspush" => [
        "url" => env('OSS_PUSH_URL', "http://120.26.100.172:7001/jxv303"),
    ],
];
