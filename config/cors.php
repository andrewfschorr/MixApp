<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS
    |--------------------------------------------------------------------------
    |
    | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
    | to accept any value.
    |
    */
    // NO LONGER USES
    // HERE FOR REFERENCE LEGACY REASONS
    // 'supportsCredentials' => true,
    // 'allowedOrigins' => ['http://localhost:3000', 'https://topofshelf.com'],
    // 'allowedOriginsPatterns' => [],
    // 'allowedHeaders' => ['*'],
    // 'allowedMethods' => ['*'],
    // 'exposedHeaders' => [],
    // 'maxAge' => 0,

     /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['http://localhost:3000', 'https://topofshelf.com'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,
];
