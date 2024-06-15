<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your retailer/individual user. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::middleware(['auth', 'retailer', 'prevent-back-history'])->prefix('retailer')->name('retailer.')->group(function () {
//     // Dashboard
//     Route::get('dashboard', [App\Http\Controllers\Retailer\DashboardController::class, 'index'])->name('dashboard');
//     Route::post('menu-setup', [App\Http\Controllers\AjaxController::class, 'menuSetup'])->name('menusetup');


//     // payouts
//     Route::get('payouts', [App\Http\Controllers\Retailer\DashboardController::class, 'payouts'])->name('payouts');
//     Route::get('payouts-list', [App\Http\Controllers\Retailer\DashboardController::class, 'payouts_list'])->name('payouts_list.export');


//     // my account
//     Route::get('profile', [App\Http\Controllers\ProfileController::class, 'profile'])->name('profile');
//     Route::post('update-password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('updatepassword');
//     Route::post('save-user-detail', [App\Http\Controllers\ProfileController::class, 'saveUserDetail'])->name('saveuserdetail');
//     Route::get('remove-profile', [App\Http\Controllers\ProfileController::class, 'removeProfile'])->name('removeprofile');
//     Route::post('notification-setting', [App\Http\Controllers\ProfileController::class, 'notificationSetting'])->name('notificationsetting');
//     Route::get('download-proof', [App\Http\Controllers\ProfileController::class, 'downloadProof'])->name('download-proof');
//     Route::get('notifications', [App\Http\Controllers\ProfileController::class, 'notifications'])->name('notifications');
//     Route::match(['get', 'post'], 'bankdetail', [App\Http\Controllers\ProfileController::class, 'bankdetail'])->name('bankdetail');
//     Route::get('switch-profile/{role}', [App\Http\Controllers\ProfileController::class, 'switch_profile'])->name('switch-profile');

//     // Product routes
//     Route::get('products/add', [App\Http\Controllers\Retailer\ProductController::class, 'create'])->name('addproduct');
//     Route::post('products', [App\Http\Controllers\Retailer\ProductController::class, 'store'])->name('saveproduct');
//     Route::get('products', [App\Http\Controllers\Retailer\ProductController::class, 'index'])->name('products');
//     Route::middleware('vendorproduct')->group(function () {
//         Route::get('products/edit/{id}', [App\Http\Controllers\Retailer\ProductController::class, 'edit'])->name('editproduct');
//         Route::post('products/{id}', [App\Http\Controllers\Retailer\ProductController::class, 'update'])->name('updateproduct');
//         Route::get('products/{id}/delete', [App\Http\Controllers\Retailer\ProductController::class, 'destroy'])->name('deleteproduct');
//         Route::get('product/{product}/status', [App\Http\Controllers\Retailer\ProductController::class, 'status'])->name('productstatus');
//     });

//     // Orders
//     Route::match(['get', 'post'], 'orders', [App\Http\Controllers\Retailer\OrderController::class, 'index'])->name('orders');
  
//     Route::middleware('retailerorder')->group(function () {
//         Route::get('order/{order}', [App\Http\Controllers\Retailer\OrderController::class, 'viewOrder'])->name('vieworder');
      
//         Route::post('order/{order}/dispute', [App\Http\Controllers\Retailer\OrderController::class, 'orderDispute'])->name('orderdispute');
       
//         Route::get('order/{order}/chat/{chat}/download', [App\Http\Controllers\Retailer\OrderController::class, 'downloadImage'])->name('downloadchatattachment');
       
//         Route::post('order/{order}/add-review', [App\Http\Controllers\Retailer\OrderController::class, 'addReview'])->name('addreview');
//         Route::get('order/{order}/image/{image}/download', [App\Http\Controllers\Retailer\OrderController::class, 'downloadAttachment'])->name('downloadattachment');
//     });
// });
// Route::match(['get', 'post'], 'retailer/order/cancel/{order}', [App\Http\Controllers\Retailer\OrderController::class, 'cancelOrder'])->name('retailer.cancelOrder');
