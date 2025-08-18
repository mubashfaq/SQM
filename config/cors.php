<?php

return [

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

 'paths' => ['api/*', 'sanctum/csrf-cookie', '*.xml'], // Add XML file path
    'allowed_methods' => ['*'], // Explicitly allow GET and OPTIONS
    'allowed_origins' => [
        'http://localhost:3000',
        'https://autopartstation.com.au',
        'https://admin.autopartstation.com.au',
        '*' // Use carefully in production
    ],



    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // Allows all headers

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false, // Set to false if you don't need credentials

];
