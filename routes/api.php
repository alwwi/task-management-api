<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::get('/register', [AuthController::class, 'register']) -> name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

//posts
Route::apiResource(name: '/posts', controller: PostController::class);

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    // Route::apiResource(name: '/posts', controller: PostController::class);
    Route::get('/posts', [PostController::class, 'index']); // Lihat semua post
    Route::post('/posts', [PostController::class, 'store']); // Tambah post
    Route::get('/posts/{id}', [PostController::class, 'show']); // Lihat post tertentu
    Route::put('/posts/{id}', [PostController::class, 'update']); // Update post
    Route::delete('/posts/{id}', [PostController::class, 'destroy']); // Hapus post
});

Route::middleware(['auth:sanctum', 'role:user'])->group(function () {
    Route::get('/posts', [PostController::class, 'index']); // Lihat semua post
    Route::get('/posts/{id}', [PostController::class, 'show']); // Lihat post tertentu
    Route::patch('/posts/{id}', [PostController::class, 'update']); // Update status post
});
