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
        'client_id' => '47277923221-cdfira2dm3trl55892ga2epq3o2ia4pi.apps.googleusercontent.com',
        'client_secret' => 'T2h78M4PvLyc_AyrNkzLj6MD',
        'redirect' => 'http://localhost/auth/google/callback'
    ],

    'facebook' => [
        'client_id' => '132105160269158',
        'client_secret' => '5371fb322f11a0bf413c2860ebc6e1ea',
        'redirect' => 'https://localhost/auth/facebook/callback'
    ],

    "apple" => [
        "client_id" => "<your_client_id>",
        "client_secret" => "<your_client_secret>",
    ],

];