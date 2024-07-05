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
Route::post('/login/{type}',[App\Http\Controllers\Api\LoginController::class,'login']);
Route::post('/reset-password/{type}', [App\Http\Controllers\Api\ForgotPasswordController::class, 'resetPassword']);
Route::post('verify-otp', [App\Http\Controllers\Api\RegisterController::class, 'verifyOtp']);
Route::post('/update-password', [App\Http\Controllers\Api\ResetPasswordController::class, 'resetPassword']);


Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/logout',[App\Http\Controllers\Api\LoginController::class,'logout']);
    
    // Product API
    Route::get('/product',[App\Http\Controllers\Api\ProductController::class,'index']);
    Route::get('/my-wishlist',[App\Http\Controllers\Api\ProductController::class,'wishlist']);
    Route::post('/add-favorite',[App\Http\Controllers\Api\ProductController::class,'addFavorite']);
    Route::post('/remove-favorite',[App\Http\Controllers\Api\ProductController::class,'removeFavorite']);
    Route::post('/category-list',[App\Http\Controllers\Api\ProductController::class,'category']);
    Route::post('/add-product',[App\Http\Controllers\Api\ProductController::class,'addProduct']);
    
    Route::post('/edit-products/{id}', [App\Http\Controllers\Api\ProductController::class, 'updateProduct']);
    Route::delete('/delete-products/{id}', [App\Http\Controllers\Api\ProductController::class, 'deleteProduct']);


    // Route::post('/products/{productId}/disable-dates', [App\Http\Controllers\Api\ProductController::class, 'addDisableDates']);
    // // Route::get('/products/{productId}', [ProductController::class, 'getProductDetails']);

    //not working
    Route::get('/view-product/{id}',[App\Http\Controllers\Api\ProductController::class,'view']);
    

    //profile APIs
    Route::get('/profile',[App\Http\Controllers\Api\ProfileController::class,'index']);
    Route::get('/profile/edit', [App\Http\Controllers\Api\ProfileController::class, 'edit']);
    Route::post('/profile/update', [App\Http\Controllers\Api\ProfileController::class, 'update']);
    Route::post('/profile/change-password', [App\Http\Controllers\Api\ProfileController::class, 'changePassword']);

    //Bank Deatils APIs
    Route::post('/bank-account', [App\Http\Controllers\Api\BankAccountController::class, 'addOrUpdateBankAccount']);
    Route::get('/bank-account/details', [App\Http\Controllers\Api\BankAccountController::class, 'getDetails']);
    

});


