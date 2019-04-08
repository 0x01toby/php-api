<?php
/**
 * Created by PhpStorm.
 * User: zhangtao
 * Date: 19-4-8
 * Time: 上午11:37
 */

return [

    'default' => env('QUEUE_DRIVER', 'redis'),

    'connections' => [

        // 使用database.redis.queue
        'redis' => [
            'driver' => 'redis',
            'connection' => 'queue',
            'queue'      => 'default',
            'retry_after' => 60,
        ]
    ],

    'failed' => [
        'database' => env('DB_CONNECTION', 'mysql'),
        'table' => env('QUEUE_FAILED_TABLE', 'failed_jobs'),
    ],
];
