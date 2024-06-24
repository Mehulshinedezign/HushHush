<?php

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register',[App\Http\Controllers\Api\RegisterController::class,'register']);
Route::post('/login',[App\Http\Controllers\Api\LoginController::class,'login']);
Route::post('/reset-password',[App\Http\Controllers\Api\ForgotPasswordController::class,'resetPassword']);

Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/logout',[App\Http\Controllers\Api\LoginController::class,'logout']);

    // Product API
    Route::get('/product',[App\Http\Controllers\Api\ProductController::class,'index']);
    Route::get('/my-wishlist',[App\Http\Controllers\Api\ProductController::class,'wishlist']);
    Route::post('/add-favorite',[App\Http\Controllers\Api\ProductController::class,'addFavorite']);
    Route::post('/remove-favorite',[App\Http\Controllers\Api\ProductController::class,'removeFavorite']);
    Route::post('/category-list',[App\Http\Controllers\Api\ProductController::class,'category']);
    Route::post('/add-product',[App\Http\Controllers\Api\ProductController::class,'addProduct']);
    Route::put('/edit-products/{id}', [App\Http\Controllers\Api\ProductController::class, 'updateProduct']);
    Route::delete('/delete-products/{id}', [App\Http\Controllers\Api\ProductController::class, 'deleteProduct']);

});


