<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::middleware(['basicAuth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/influencers', [App\Http\Controllers\HomeController::class, 'influencers'])->name('influencers');
    Route::resource('ads', \App\Http\Controllers\AdController::class)->middleware('');
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    Route::resource('social-media', \App\Http\Controllers\SocialMediaController::class);
}
);
