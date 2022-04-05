<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

$router->group(['prefix' => 'users'], function () use ($router) {
    $router->post('/', [UserController::class, 'register']);
    $router->post('/login', [UserController::class, 'login']);
});

$router->group(['prefix' => 'user', 'middleware' => 'auth:sanctum'], function () use ($router) {
    $router->get('/', [UserController::class, 'me']);
    $router->put('/', [UserController::class, 'update']);
});

$router->group(['prefix' => 'articles', 'middleware' => 'auth:sanctum'], function () use ($router) {
    $router->post('/', [ArticleController::class, 'create']);
    $router->post('/{article:slug}/favorite', [ArticleController::class, 'favorite']);
});
