<?php
/**
 * Created by PhpStorm.
 * User: zhangtao
 * Date: 19-4-10
 * Time: 上午11:00
 */
return [

    // 配置对称加密 详细代码见 encrypter => Illuminate\Encryption\EncryptionServiceProvider
    'key'       => env('APP_KEY'),
    'cipher'    => 'AES-256-CBC',
    'url'       => env('APP_URL')
];
