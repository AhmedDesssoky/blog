<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\SubscriberController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// CATEGORY MODULE
Route::get('/categories', CategoryController::class);

// CONTACT MODULE
Route::get('/contacts', [ContactController::class, 'index']);
Route::post('/contacts/store', [ContactController::class, 'store']);

// SUBSCRIBER MODULE
Route::get('/subscribers', [SubscriberController::class, 'index']);
Route::post('/subscribers/store', [SubscriberController::class, 'store']);

// AUTH MODULE
Route::controller(AuthController::class)->group(function(){
    Route::post('/register','register');
    Route::post('/login','login');
    Route::post('/logout','logout')->middleware('auth:sanctum');

});

// BLOG MODULE


Route::prefix('blogs')->controller(BlogController::class)->group(function(){
    Route::get('/','index');
    Route::get('/latest','latest');
    Route::get('/search','search');

    Route::middleware('auth:sanctum')->group(function(){
        Route::post('/create','create');
        Route::post('/delete/{blogId}','update');
        Route::get('/delete/{blogId}','delete');
        Route::get('/myBlogs','myBlogs');

    });


});
