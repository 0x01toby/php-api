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


$router->get('/', function () use ($router) {

 try {
     $users  = DB::connection('mysql')->table('users')->get();
     dd($users);
     $config = Config::get('database');
 } catch (\Exception $e) {
     dd($e->getMessage());
 }

 dd($config);


    return $router->app->version();
});
