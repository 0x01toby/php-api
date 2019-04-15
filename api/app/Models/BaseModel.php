<?php
/**
 * Created by PhpStorm.
 * User: zhangtao
 * Date: 19-4-15
 * Time: 下午2:29
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{

    protected static function boot()
    {
        parent::boot();
    }
}
