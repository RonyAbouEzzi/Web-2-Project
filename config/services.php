<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel'              => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Stripe
    |--------------------------------------------------------------------------
    */

    'stripe' => [
        'key'    => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Social OAuth Providers (Laravel Socialite)
    |--------------------------------------------------------------------------
    */

    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('GOOGLE_REDIRECT_URI', '/auth/google/callback'),
    ],

    'github' => [
        'client_id'     => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect'      => env('GITHUB_REDIRECT_URI', '/auth/github/callback'),
    ],

    'google_maps' => [
        'api_key' => env('GOOGLE_MAPS_API_KEY'),
    ],

    'azure_document_intelligence' => [
        'endpoint' => env('AZURE_DOC_INTELLIGENCE_ENDPOINT'),
        'key' => env('AZURE_DOC_INTELLIGENCE_KEY'),
        'api_version' => env('AZURE_DOC_INTELLIGENCE_API_VERSION', '2024-11-30'),
        'timeout_seconds' => (int) env('AZURE_DOC_INTELLIGENCE_TIMEOUT', 20),
        'poll_attempts' => (int) env('AZURE_DOC_INTELLIGENCE_POLL_ATTEMPTS', 15),
        'poll_interval_ms' => (int) env('AZURE_DOC_INTELLIGENCE_POLL_INTERVAL_MS', 900),
    ],

    'ocr_space' => [
        'endpoint' => env('OCR_SPACE_ENDPOINT', 'https://api.ocr.space/parse/image'),
        'api_key' => env('OCR_SPACE_API_KEY'),
        'language' => env('OCR_SPACE_LANGUAGE', 'auto'),
        'engine' => env('OCR_SPACE_ENGINE', '2'),
        'timeout_seconds' => (int) env('OCR_SPACE_TIMEOUT', 20),
    ],

];
