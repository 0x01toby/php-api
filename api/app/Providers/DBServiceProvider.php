<?php
/**
 * Created by PhpStorm.
 * User: zhangtao
 * Date: 19-4-5
 * Time: 上午11:23
 */

namespace App\Providers;


use Carbon\Laravel\ServiceProvider;
use Illuminate\Database\DatabaseServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Facade;

class DBServiceProvider extends ServiceProvider
{

    public function register()
    {
       $this->app->register(DatabaseServiceProvider::class);
        Facade::setFacadeApplication($this->app);
        class_alias(DB::class, "DB");
    }

}
