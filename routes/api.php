<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('posts', PostController::class);
// Route::put('posts/{post}', [PostController::class, 'update']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
// Nested Comment routes under posts
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('posts.comments', CommentController::class)
        ->only(['index', 'store', 'show', 'update', 'destroy']);
});
Route::get('/top-users', [ReportController::class, 'topUsers']);
Route::get('/top-posts', [ReportController::class, 'topPosts']);
