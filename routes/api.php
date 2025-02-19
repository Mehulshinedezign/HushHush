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

Route::post('/register', [App\Http\Controllers\Api\RegisterController::class, 'register']);
Route::post('/login/{type}', [App\Http\Controllers\Api\LoginController::class, 'login'])->middleware('prevent.admin');
Route::post('/reset-password/{type}', [App\Http\Controllers\Api\ForgotPasswordController::class, 'resetPassword']);
Route::post('verify-otp/{type}', [App\Http\Controllers\Api\RegisterController::class, 'verifyOtp']);
Route::post('/update-password', [App\Http\Controllers\Api\ResetPasswordController::class, 'resetPassword']);
Route::post('/resend-otp/{type}', [App\Http\Controllers\Api\ResetPasswordController::class, 'resentOtp']);


Route::get('/guest', [App\Http\Controllers\Api\ProductController::class, 'guestIndex']);
Route::get('/guest/product-details/{id}', [App\Http\Controllers\Api\ProductController::class, 'getGuestAllProductsById']);
Route::get('/guest/lender-profile/{id}', [App\Http\Controllers\Api\ProductController::class, 'guestLenderInfo']);

Route::post('/logout', [App\Http\Controllers\Api\LoginController::class, 'logout']);
Route::middleware(['auth:sanctum', 'prevent.admin'])->group(function () {
    Route::get('/product', [App\Http\Controllers\Api\ProductController::class, 'index']);
    // Product API
    Route::get('/my-wishlist', [App\Http\Controllers\Api\ProductController::class, 'wishlist']);
    Route::post('/add-favorite/{id}', [App\Http\Controllers\Api\ProductController::class, 'addFavorite']);
    Route::post('/remove-favorite', [App\Http\Controllers\Api\ProductController::class, 'removeFavorite']);
    Route::post('/category-list', [App\Http\Controllers\Api\ProductController::class, 'category']);
    Route::post('/add-product', [App\Http\Controllers\Api\ProductController::class, 'addProduct']);

    Route::post('/edit-products/{id}', [App\Http\Controllers\Api\ProductController::class, 'updateProduct']);
    Route::post('/delete-products/{id}', [App\Http\Controllers\Api\ProductController::class, 'deleteProduct']);


    // Route::post('/products/{productId}/disable-dates', [App\Http\Controllers\Api\ProductController::class, 'addDisableDates']);
    // // Route::get('/products/{productId}', [ProductController::class, 'getProductDetails']);

    Route::get('/product-category', [App\Http\Controllers\Api\ProductController::class, 'getFormData']);
    Route::get('/view-product/{id}', [App\Http\Controllers\Api\ProductController::class, 'view']);
    Route::get('/products/listing', [App\Http\Controllers\Api\ProductController::class, 'getAllProducts'])->name('apiIndex');
    Route::get('/product-details/{id}', [App\Http\Controllers\Api\ProductController::class, 'getAllProductsById']);
    Route::get('/editProduct/{id}', [App\Http\Controllers\Api\ProductController::class, 'editProduct']);
    Route::get('/user/products/listing', [App\Http\Controllers\Api\ProductController::class, 'getAuthUserProducts']);
    Route::get('/product/checkout/{id}', [App\Http\Controllers\Api\ProductController::class, 'checkout']);

    Route::get('/lender-profile/{id}', [App\Http\Controllers\Api\LenderController::class, 'index']);

    //Query
    Route::post('/queries', [App\Http\Controllers\Api\QueryController::class, 'store']);
    Route::get('/queries/user', [App\Http\Controllers\Api\QueryController::class, 'queriesByUser']);
    Route::get('/queries/for-user', [App\Http\Controllers\Api\QueryController::class, 'queriesForUser']);
    Route::post('/queries/{id}/status/{type}', [App\Http\Controllers\Api\QueryController::class, 'updateQueryStatus']);

    Route::post('/bookings', [App\Http\Controllers\Api\QueryController::class, 'bookings']);
    Route::get('/orders', [App\Http\Controllers\Api\QueryController::class, 'orderManagement']);
    Route::get('/your-product-booked', [App\Http\Controllers\Api\QueryController::class, 'booked']);
    Route::post('products/{id}/ratings', [App\Http\Controllers\Api\ProductController::class, 'ratings']);
    Route::get('/order/details/{id}', [App\Http\Controllers\Api\ProductController::class, 'orderDetails']);
    Route::post('order/{id}/upload-retailer-images/{type}', [App\Http\Controllers\Api\OrderController::class, 'uploadRetailerImages']);
    Route::post('order/{id}/upload-customer-images/{type}', [App\Http\Controllers\Api\OrderController::class, 'uploadCustomerImages']);
    Route::post('/verify/{id}/images/{type}', [App\Http\Controllers\Api\OrderController::class, 'retailerVerifyImage']);
    Route::post('/customerverify/{id}/images/{type}', [App\Http\Controllers\Api\OrderController::class, 'customerVerifyImage']);

    Route::post('/order/{order}/dispute', [App\Http\Controllers\Api\OrderController::class, 'orderDisputeApi']);
    Route::get('/orders/disputed/{type}', [App\Http\Controllers\Api\OrderController::class, 'getDisputedOrders']);
    Route::post('/cancel/orders/{id}', [App\Http\Controllers\Api\OrderController::class, 'cancelOrderApi']);

    Route::post('/payment-intent/{id}', [App\Http\Controllers\Api\StripeController::class, 'createPaymentIntent']);
    Route::post('/user-intent', [App\Http\Controllers\Api\StripeController::class, 'createIntent']);

    //profile APIs
    Route::get('/profile', [App\Http\Controllers\Api\ProfileController::class, 'index']);
    Route::get('/profile/edit', [App\Http\Controllers\Api\ProfileController::class, 'edit']);
    Route::post('/profile/update', [App\Http\Controllers\Api\ProfileController::class, 'update']);
    Route::post('/profile/change-password', [App\Http\Controllers\Api\ProfileController::class, 'changePassword']);
    Route::get('user/stats', [App\Http\Controllers\Api\ProfileController::class, 'stats']);
    Route::post('user/delete', [App\Http\Controllers\Api\ProfileController::class, 'destory']);
    Route::get('user/notification', [App\Http\Controllers\Api\ProfileController::class, 'userNotification']);
    Route::post('user/update/notification', [App\Http\Controllers\Api\ProfileController::class, 'updateNotification']);
    Route::get('user/earnings', [App\Http\Controllers\Api\ProfileController::class, 'earnings']);
    Route::get('user/address', [App\Http\Controllers\Api\ProfileController::class, 'getAddress']);
    Route::post('user/save/address', [App\Http\Controllers\Api\ProfileController::class, 'saveAddress']);
    Route::post('user/address/delete/{id}', [App\Http\Controllers\Api\ProfileController::class, 'addressDestroy']);


    Route::get('testing', [App\Http\Controllers\Api\ProfileController::class, 'test']);
    Route::post('pushToken/add', [App\Http\Controllers\Api\ProfileController::class, 'addFcm']);

    Route::post('/report/product/{id}', [App\Http\Controllers\Api\ProductController::class, 'reportProduct']);
    Route::post('/add-brand', [App\Http\Controllers\Api\ProductController::class, 'addBrand']);

    //Bank Deatils APIs
    Route::post('/bank-account', [App\Http\Controllers\Api\BankAccountController::class, 'addOrUpdateBankAccount']);
    Route::get('/bank-account/details', [App\Http\Controllers\Api\BankAccountController::class, 'getDetails']);

    Route::post('/stripe/onboarding/redirect', [App\Http\Controllers\Api\StripeController::class, 'redirectToStripe'])->name('api.stripe.onboarding.redirect');
    Route::get('/stripe/onboarding/refresh', [App\Http\Controllers\Api\StripeController::class, 'refreshOnboarding'])->name('api.stripe.onboarding.refresh');
    Route::get('/stripe/onboarding/complete', [App\Http\Controllers\Api\StripeController::class, 'completeOnboarding'])->name('api.stripe.onboarding.complete');
});
Route::post('/stripe/webhook', [App\Http\Controllers\Api\StripeWebhookController::class, 'handleWebhook'])->name('api.stripe.webhook');

use App\Http\Controllers\Api\StripeIdentityController;

// Route to create a verification session
Route::post('/stripe/verification-session', [StripeIdentityController::class, 'createVerificationSession'])
    ->middleware('auth:sanctum'); // Add auth middleware if necessary

// Route to handle Stripe webhooks
Route::post('/stripe/webhook', [StripeIdentityController::class, 'handleWebhook']);

// Route to handle success redirection
Route::get('/verification-success', [StripeIdentityController::class, 'verificationSuccess'])
    ->name('api.verification.success');

