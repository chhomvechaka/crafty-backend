<?php

return [
    'default' => env('FIREBASE_PROJECT', 'crafty-studio'),

    'projects' => [
        'crafty-studio' => [
            'credentials' => [
                'file' => env('FIREBASE_CREDENTIALS'),
                'auto_discovery' => true,
            ],
            'database_url' => env('FIREBASE_DATABASE_URL', 'https://crafy-studio-default-rtdb.firebaseio.com'),
        ],
    ],
];
