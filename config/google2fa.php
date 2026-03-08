<?php

/**
 * Google2FA Configuration
 * Published by: php artisan vendor:publish --provider="PragmaRX\Google2FALaravel\ServiceProvider"
 */

return [

    /*
    |--------------------------------------------------------------------------
    | One Time Password (OTP) Window
    |--------------------------------------------------------------------------
    | Number of QR code periods (30-second windows) to check on each side of
    | the current period. Default: 0 (strict — only current window accepted).
    | Set to 1 to allow ±30 seconds clock drift.
    */

    'window' => 1,

    /*
    |--------------------------------------------------------------------------
    | OTP Lifetime (minutes)
    |--------------------------------------------------------------------------
    | Once verified, the user will not be prompted again for this many minutes.
    | Set to 0 to ask on every request.
    */

    'lifetime' => 0,

    /*
    |--------------------------------------------------------------------------
    | Keep OTP Alive
    |--------------------------------------------------------------------------
    | If true, each request resets the OTP lifetime timer.
    */

    'keep_alive' => true,

    /*
    |--------------------------------------------------------------------------
    | Auth Container Binding
    |--------------------------------------------------------------------------
    */

    'auth' => 'auth',

    /*
    |--------------------------------------------------------------------------
    | OTP User Column
    |--------------------------------------------------------------------------
    | The column on your users table that stores the Google2FA secret.
    */

    'otp_user_column' => 'google2fa_secret',

    /*
    |--------------------------------------------------------------------------
    | QR Code Image Backend
    |--------------------------------------------------------------------------
    | Options: 'svg', 'eps', 'png'
    */

    'qrcode_image_backend' => \PragmaRX\Google2FALaravel\Support\Constants::QRCODE_IMAGE_BACKEND_SVG,

];
