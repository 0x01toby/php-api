<?php
/**
 * Created by PhpStorm.
 * User: zhangtao
 * Date: 19-4-4
 * Time: 下午10:53
 */

namespace App\Providers;


use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->registerConfigFacade();
        $this->loadConfigs();

    }

    /**
     * 读取config目录下所有的文件
     */
    protected function loadConfigs()
    {
        $config_path = $this->app->getConfigurationPath();
        foreach (scandir($config_path) as $file_name) {
            if ($file_name === '.' || $file_name === '..') {
                continue;
            }
            $name = str_replace('.php', '', $file_name);
            $this->app->configure($name);
        }
    }

    /**
     * 配置Config Facade 可以使用 Config 读取配置文件
     */
    protected function registerConfigFacade()
    {
        $this->app->singleton('config', function () {
            return new Repository();
        });
        $this->app->withFacades(true, [Config::class => 'Config']);
    }

}
