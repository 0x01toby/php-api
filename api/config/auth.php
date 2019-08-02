<?php
/**
 * Created by PhpStorm.
 * User: zhangtao
 * Date: 19-4-8
 * Time: 下午7:22
 */
return [
    'defaults' => [
        'guard' => 'custom_jwt',
    ],

    'guards' => [
        'custom_api' => [
            'driver' => 'custom_api_driver', // 自定义driver 需要通过 Auth::extends来实现
            'provider' => 'custom_api'
        ],
        'custom_jwt' => [                   // api/app/Providers/AuthServiceProvider.php
            'driver' => 'custom_jwt_driver',
            'provider' => 'custom_jwt',
        ]
    ],

    'providers' => [
        'custom_api' => [
            'model' => \App\Models\User::class,
            'driver' => 'custom_api_provider'
        ],
        'custom_jwt' => [
            'model' => \App\Models\User::class,
            'driver' => 'custom_jwt_provider'
        ]
    ],
];
