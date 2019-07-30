<?php

return [
    // token 有效期时间（单位s）
    "expire" => 2592000,

    // token 刷新时间
    'refresh_ttl' => 15 * 24 * 3600,

    // 加密方式
    'signer' => 'sha256',

    'jwt_secret' => env('JWT_SECRET')
];
