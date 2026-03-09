<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

use App\Http\Controllers\PostController;

Route::get('/',[PostController::class,'index']);
Route::get('/dashboard-data', [PostController::class, 'dashboardData']);

Route::post('/posts',[PostController::class,'store']);
Route::patch('/posts/{post}', [PostController::class, 'update']);
Route::delete('/posts/{post}', [PostController::class, 'destroy']);
Route::post('/pubsub/publish', [PostController::class, 'publish']);
Route::patch('/pubsub/messages/{messageId}', [PostController::class, 'updatePubSubMessage']);
Route::delete('/pubsub/messages/{messageId}', [PostController::class, 'destroyPubSubMessage']);
