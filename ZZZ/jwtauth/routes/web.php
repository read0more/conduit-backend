<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$router->get('/', function () use ($router) {
    return view('welcome');
});


$router->group(['prefix' => 'users'], function () use ($router) {
    $router->post('/', [UserController::class, 'register']);
    $router->post('/login', 'UserController@login');
});

$router->group(['prefix' => 'user', 'middleware' => 'auth'], function () use ($router) {
    $router->get('/', 'UserController@me');
    $router->put('/', 'UserController@update');
});

$router->group(['prefix' => 'articles', 'middleware' => 'auth'], function () use ($router) {
    $router->post('/', 'ArticleController@create');
});
