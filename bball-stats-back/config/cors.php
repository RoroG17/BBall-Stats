<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | This file is used to configure the settings for cross-origin requests.
    | You can control which origins, methods, and headers are allowed.
    |
    */

    'paths' => ['*'],

    'allowed_methods' => ['*'], // Autorise toutes les méthodes (GET, POST, etc.)

    'allowed_origins' => ['http://localhost:9000'], // Autorise Quasar (port 9000)

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // Autorise tous les headers (ex. Content-Type)

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
