<?php

return [
    "default" => env('DB_CONNECTION', 'mysql'),


    'migrations' => 'migrations',

    // 数据库配置
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
   ],


    // redis 配置
    'redis' => [

        // redis 队列配置
        'queue' => [
            'host'      => env("REDIS_QUEUE_HOST", '127.0.0.1'),
            'password'  => env("REDIS_QUEUE_PASS", null),
            "port"      => env("REDIS_QUEUE_PORT", '6379'),
            'database'  => env("REDIS_QUEUE_DATABASE", 3)
        ]
    ]
];
