<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

//Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/', function () {
    return view('welcome');
});





/*
Route::get('/post', function () {
    return redirect()->route('posts.index');
});*/

//Route::resource('posts', PostController::class);
