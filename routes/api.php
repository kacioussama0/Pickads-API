<?php

use Illuminate\Support\Facades\Route;

Route::post('register',[\App\Http\Controllers\Api\AuthController::class,'register']);
Route::post('login',[\App\Http\Controllers\Api\AuthController::class,'login']);
Route::get('/profile/{username}',[\App\Http\Controllers\Api\UserController::class,'profile']);
Route::post('/forget-password',[\App\Http\Controllers\Api\AuthController::class,'forgetPassword']);
Route::post('/password/reset',[\App\Http\Controllers\Api\AuthController::class,'resetPassword']);
Route::get('/influencers',[\App\Http\Controllers\Api\SiteController::class,'influencers']);
Route::post('/put-like',[\App\Http\Controllers\Api\LikeController::class,'like']);
Route::post('/put-unlike',[\App\Http\Controllers\Api\LikeController::class,'unLike']);
Route::get('/who-like/{fingerprint}',[\App\Http\Controllers\Api\SiteController::class,'whoLike']);


Route::middleware('auth:api')->prefix('user')->group(function() {
    Route::post('update/password',[\App\Http\Controllers\Api\UserController::class,'updatePassword']);
    Route::get('social-media',[\App\Http\Controllers\Api\UserController::class,'socialMedia']);
    Route::post('update/profile',[\App\Http\Controllers\Api\UserController::class,'updateProfile']);
    Route::post('update/avatar',[\App\Http\Controllers\Api\UserController::class,'updateAvatar']);
    Route::get('/',[\App\Http\Controllers\Api\UserController::class,'user']);
});


Route::get('categories/{category}/users',[\App\Http\Controllers\Api\CategoryController::class,'showUsers']);
Route::resource('social-media', \App\Http\Controllers\Api\SocialMediaController::class);
Route::post('send-message',[\App\Http\Controllers\Api\MessageController::class,'send']);
Route::resource('categories', \App\Http\Controllers\Api\CategoryController::class);
Route::resource('ads', \App\Http\Controllers\Api\AdController::class);
Route::delete('delete-message/{message}',[\App\Http\Controllers\Api\MessageController::class,'delete']);

