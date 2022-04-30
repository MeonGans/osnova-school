<?php

use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::get('news', [\App\Http\Controllers\NewsController::class, 'index'])->name('news.index');
Route::get('news/{id}', [\App\Http\Controllers\NewsController::class, 'show'])->name('news.show');

Route::get('menu', [\App\Http\Controllers\MenuController::class, 'index'])->name('menu.index');
Route::get('menu1', [\App\Http\Controllers\MenuController::class, 'nav_sort'])->name('menu.nav_sort');
Route::post('menu', [\App\Http\Controllers\MenuController::class, 'update'])->name('menu.update');

Route::get('page/{id}', [\App\Http\Controllers\PageController::class, 'show'])->name('page.show');

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);

Route::middleware('admin')->group(function () {

    Route::apiResource('folder', \App\Http\Controllers\FolderController::class)
        ->except('show');

    Route::apiResource('link', \App\Http\Controllers\LinkController::class)
        ->except('show');

    Route::apiResource('page', \App\Http\Controllers\PageController::class)
        ->except('show');

    Route::apiResource('news', \App\Http\Controllers\NewsController::class)
        ->except('index', 'show');
    Route::post('news/{id}/restore', [\App\Http\Controllers\NewsController::class, 'restore']);
    Route::post('logout', [RegisterController::class, 'logout']);
});

