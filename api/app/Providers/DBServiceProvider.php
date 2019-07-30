<?php
/**
 * Created by PhpStorm.
 * User: zhangtao
 * Date: 19-4-5
 * Time: 上午11:23
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\DatabaseServiceProvider;
use Illuminate\Support\Facades\DB;

class DBServiceProvider extends ServiceProvider
{

    public function register()
    {
       $this->app->register(DatabaseServiceProvider::class);
       $this->app->withFacades(true, [DB::class => 'DB']);
    }

}
