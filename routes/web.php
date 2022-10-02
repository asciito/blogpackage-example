<?php

use asciito\BlogPackage\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;


Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
