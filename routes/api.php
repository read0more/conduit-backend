<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfilesController;

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
$missingArticle = function (Request $request) {
    $articleSlug = $request->route()->parameter('article');
    return response()->json(['error' => "Cannot found article: $articleSlug."], 404);
};

$missingUser = function (Request $request) {
    $username = $request->route()->parameter('user');
    return response()->json(['error' => "Cannot found user: $username."], 404);
};

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

$router->group(['prefix' => 'articles'], function () use ($missingArticle, $router) {
    $router->get('/', [ArticleController::class, 'read']);
    $router->get('/{article:slug}/comments', [CommentController::class, 'read'])->missing($missingArticle);
});

$router->group(['prefix' => 'articles', 'middleware' => 'auth:sanctum'], function () use ($missingArticle, $router) {
    $router->post('/', [ArticleController::class, 'create']);
    $router->put('/{article:slug}', [ArticleController::class, 'update'])->missing($missingArticle);
    $router->delete('/{article:slug}', [ArticleController::class, 'delete'])->missing($missingArticle);
    $router->post('/{article:slug}/favorite', [ArticleController::class, 'favorite'])->missing($missingArticle);
    $router->post('/{article:slug}/comments', [CommentController::class, 'create'])->missing($missingArticle);
    $router->delete('/{article:slug}/comments/{comment}', [CommentController::class, 'delete'])->missing($missingArticle);
});


$router->group(['prefix' => 'profiles'], function () use ($missingUser, $router) {
    $router->get('/{user:username}', [ProfilesController::class, 'get'])->missing($missingUser);
});

$router->group(['prefix' => 'profiles', 'middleware' => 'auth:sanctum'], function () use ($missingUser, $router) {
    $router->post('/{user:username}/follow', [ProfilesController::class, 'follow'])->missing($missingUser);
});
