<?php

 use App\Http\Controllers\PostController;
 use App\Http\Controllers\PublicController;
 use App\Http\Controllers\TagController;
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Route;

 Route::get('/user', function (Request $request) {
     return $request->user();
 })->middleware('auth:sanctum');

 Route::get('/posts', [PublicController::class, 'index']);
 Route::get('/posts/{post}', [PublicController::class, 'post']);
 Route::post('/post/{post}/comment', [PublicController::class, 'comment']);
 Route::post('/post/{post}/like', [PublicController::class, 'like']);
 Route::apiResource('/admin/posts', PostController::class);
 Route::get('/tags', [TagController::class, 'index']);
