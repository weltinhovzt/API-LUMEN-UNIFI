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
    return $router->app->version();
});


$router->group(['namespace' => 'Auth', 'prefix' => 'api/auth'], function () use ($router) {
    $router->post('signin', 'SignInController');
    $router->post('signup', 'SignUpController');
});

$router->group(['namespace' => 'Ubiquiti', 'prefix' => 'api/auth', 'middleware' => 'auth'], function () use ($router) {
    $router->post('guest', 'GuestController');
});
