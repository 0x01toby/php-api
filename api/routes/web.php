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
use Illuminate\Support\Facades\Config;
use App\Http\Middleware\Authenticate;

function v1() {
    Route::get('example-index', "ExampleController@index");
}

// 绑定中间件
function bindMiddleware(Router $router)
{
    $router->app->routeMiddleware([
        'auth' => Authenticate::class
    ]);
}

/**
 * @var $router Router
 */

bindMiddleware($router);

Route::get('/', function () use ($router) {
    return $router->app->version();
});

// {{domain}}/api/v1/
Route::group(['prefix' => 'api'], function () {
    Route::group(['prefix' => 'v1', 'middleware' => 'auth'], function () {
        v1();
    });
});

// api.{{domain}}/v1/
Route::group(['domain' => 'api' . Config::get('app.domain')], function () {
    Route::group(['prefix' => 'v1', 'middleware' => 'auth'], function () {
        v1();
    });
});
