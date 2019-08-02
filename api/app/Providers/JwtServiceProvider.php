<?php


namespace App\Providers;


use App\Extensions\Auth\Jwt\JwtService;
use Illuminate\Support\ServiceProvider;

class JwtServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton(JwtService::class, function () {
            return new JwtService($this->app);
        });
    }
}
