<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
    Route::post('posts', [PostController::class, 'storePost']);
    Route::get('posts', [PostController::class, 'indexPost']);
    Route::get('posts/{id}', [PostController::class, 'viewPost']);

    Route::post('/register', [AuthController::class, 'storeRegister']);
    Route::get('/register', [AuthController::class, 'indexRegister']);

    Route::post('/tasks', [TaskController::class, 'storeTask']);
    Route::get('/tasks', [TaskController::class, 'indexTask']);
    Route::patch('/tasks/{id}', [TaskController::class, 'updateTask']);

