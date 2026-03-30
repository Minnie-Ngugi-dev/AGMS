<?php

return [

    /*
    |--------------------------------------------------------------------------
    | M-Pesa Daraja API Configuration
    |--------------------------------------------------------------------------
    |
    | Get your credentials from: https://developer.safaricom.co.ke
    | For sandbox testing use the sandbox credentials.
    | For production use your live credentials.
    |
    */

    // API Environment: 'sandbox' or 'production'
    'env' => env('MPESA_ENV', 'sandbox'),

    // Consumer Key from Daraja portal
    'consumer_key' => env('MPESA_CONSUMER_KEY', ''),

    // Consumer Secret from Daraja portal
    'consumer_secret' => env('MPESA_CONSUMER_SECRET', ''),

    // Business Shortcode (Paybill or Till number)
    // Sandbox default: 174379
    'shortcode' => env('MPESA_SHORTCODE', '174379'),

    // Lipa Na M-Pesa Passkey
    // Sandbox default passkey below
    'passkey' => env('MPESA_PASSKEY', 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919'),

    // Callback URL — must be HTTPS and publicly accessible
    // Use ngrok for local testing: ngrok http 8000
    'callback_url' => env('MPESA_CALLBACK_URL', 'https://yourdomain.co.ke/api/mpesa/callback'),

    /*
    |--------------------------------------------------------------------------
    | API Base URLs (auto-selected based on env)
    |--------------------------------------------------------------------------
    */
    'base_url' => [
        'sandbox'    => 'https://sandbox.safaricom.co.ke',
        'production' => 'https://api.safaricom.co.ke',
    ],

];