<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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


$router->group(['prefix' => 'users'], function () use ($router) {
    $router->post('/', 'UserController@register');
    $router->post('/login', 'UserController@login');
});

$router->get('/user', [
    'middleware' => 'auth',
    'uses' => 'UserController@me'
]);


$router->put('user', [
    'middleware' => 'auth',
    'uses' => 'UserController@update'
]);
