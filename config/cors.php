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

    'paths' => ['api/*', 'admin/*', '*', 'sanctum/csrf-cookie'], // Adjust the paths as necessary
    'allowed_methods' => ['*'], // Allows all methods
    'allowed_origins' => ['http://localhost:3000'], // Adjust to match your frontend URL
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'], // Allows all headers
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true, // If you are using cookies/session auth

];
