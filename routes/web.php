<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

use App\Http\Controllers\PostController;

Route::get('/',[PostController::class,'index']);

Route::post('/posts',[PostController::class,'store']);
