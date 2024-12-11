<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StatsController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/stats', [StatsController::class, 'index']);

Route::get('login', function () {
    return response()->json([
        'message' => 'You are not logged in. Please log in and try again'
    ], 401);
})->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('posts/trashed', [PostController::class, 'trashed']);
    Route::post('posts/{postId}/restore', [PostController::class, 'restore']);
    Route::apiResource('tags', TagController::class);
    Route::apiResource('posts', PostController::class);
});