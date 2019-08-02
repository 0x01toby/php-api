<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
use Laravel\Lumen\Routing\Router;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\DefaultGuard;
use \App\Http\Middleware\UserAgent;

/**
 * 注册中间件
 * @param Router $router
 */
$bind = function(Router $router)
{
    $router->app->routeMiddleware([
        'auth' => Authenticate::class,
        'user-agent' => UserAgent::class,
        'default-guard' => DefaultGuard::class,
    ]);
};
/** @var $router Router */
$bind($router);

Route::post("login", "LoginController@login");

Route::group(['prefix' => 'api/v1', 'namespace' => "Api\\V1", "middleware" => ['user-agent', 'default-guard', 'auth']], function () {
    \App\Http\Router\Api\V1\Router::route();
});
