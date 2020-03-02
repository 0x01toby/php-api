<?php

use \App\Extensions\Logger\Logging\ConfigureDailyLogging;
return [
    // 默认 channels 为 daily
    'default' => env('APP_LOG', 'daily'),

    // daily
    'channels' => [
        // daily 方式的日志
        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/lara-test.log'),
            'level' => 'debug',
            'tap'   =>  [ConfigureDailyLogging::class],
            'days' => 5,
        ],
        // web hook
        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => 'critical',
        ],
    ],
];
