<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\AuthManager;
use App\Extensions\Auth\CustomGuard;
use Illuminate\Support\ServiceProvider;
use App\Extensions\Auth\CustomUserProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * 实现自定义的user provider
         */
        $this->app['auth']->provider('custom_api_provider', function ($app, $config) {
            $user_provider = new CustomUserProvider($app, $config);
            return $user_provider;
        });

        /**
         * 实现自定义的 guard creator 的driver
         */
        $this->app['auth']->extend('custom_api_driver', function ($app, $name, $config) {

            // $app app 对象， $name guard字符串 $config auth.guards.$name 配置文件
            /** @var  $app['auth'] AuthManager */
            $guard = new CustomGuard($name, $app['auth']->createUserProvider($config['provider']));

            if (method_exists($guard, 'setRequest')) {
                $guard->setRequest($app['request']);
            }

            return $guard;
        });
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        /** @var AuthManager $auth */
       /* $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->input('api_token')) {
                return User::where('api_token', $request->input('api_token'))->first();
            }
        });*/
    }
}
