<?php

return [
    "default" => env('DB_CONNECTION', 'mysql'),


    'migrations' => 'migrations',

    "connections" => [
        'mysql' => [
            'driver' => 'mysql',
            'host'   => env('DB_HOST'),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset'  => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]
   ]
];