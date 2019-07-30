<?php


namespace App\Extensions\Helper;


use \Illuminate\Support\Facades\Request;
use Ramsey\Uuid\Uuid;

class Helpers
{
    /**
     * 获取当前url
     */
    public static function getCurrentUrl()
    {
        return Request::fullUrl();
    }

    /**
     * 获取uuid
     * @return string
     */
    public static function getUuid()
    {
        try {
            return str_replace("-", "", Uuid::uuid4()->toString());
        } catch (\Exception $e) {
            return md5(time() . mt_rand(1, 9999999));
        }
    }

    /**
     * 获取当前时间戳
     * @return int
     */
    public static function getNowTime()
    {
        static $now_time;
        if ($now_time) {
            return $now_time;
        }
        return $now_time = time();
    }

}
