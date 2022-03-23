<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request)
// {
//     return $request->user();
// });

// Not authentication
Route::prefix('users')->group(function ()
{
    Route::post('/', [UserController::class, 'store']);
    Route::post('login', [UserController::class, 'login']);
});
Route::prefix('articles')->group(function ()
{
    Route::get('/', [ArticleController::class, 'index']);
    Route::get('{article}', [ArticleController::class, 'show']);
});
Route::prefix('tags')->group(function ()
{
    Route::get('/', [TagController::class, 'index']);
});

// Authentication
Route::middleware('auth')->group(function ()
{
    Route::prefix('user')->group(function ()
    {
        Route::get('/', [UserController::class, 'show']);
        Route::put('/', [UserController::class, 'update']);
    });

    Route::prefix('articles')->group(function ()
    {
        Route::post('/', [ArticleController::class, 'store']);
        Route::patch('{article}', [ArticleController::class, 'update']);
        Route::delete('{article}', [ArticleController::class, 'destroy']);
    });
});
