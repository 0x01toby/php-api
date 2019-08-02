<?php


namespace App\Http\Router\Api\V1;


use Illuminate\Support\Facades\Route;

class Router
{
    public static function route()
    {
        Route::get("example", "ExampleController@example");
    }
}
