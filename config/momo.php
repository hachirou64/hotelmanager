<?php

return [
    // Use sandbox mode by default. Set to false in production and provide real credentials.
    'sandbox' => env('MOMO_SANDBOX', true),
    'endpoint' => env('MOMO_ENDPOINT', 'https://api.momo.example'),
    'client_id' => env('MOMO_CLIENT_ID'),
    'client_secret' => env('MOMO_CLIENT_SECRET'),
    // Optional additional config
    'timeout' => env('MOMO_TIMEOUT', 10),
    // Shared secret for webhook verification (HMAC). Set in .env in production.
    'webhook_secret' => env('MOMO_WEBHOOK_SECRET', null),
    // Supported providers (logical names). Map these to real endpoints in MomoService when implementing provider SDK.
    'providers' => [
        'mtn',
        'moov',
        'celtis',
    ],
];
