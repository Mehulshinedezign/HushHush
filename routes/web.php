<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StripeOnboardingController;

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

// Route::get('/', function () {
//     return redirect()->route('login');
// });
Route::get("testing", [App\Http\Controllers\HomeController::class, 'testing'])->name('test');
Route::get('order/earning/transaction', [StripeOnboardingController::class, 'earningtransaction'])->name('order.earning.transaction');
Route::get('order/spent/transaction', [StripeOnboardingController::class, 'transaction'])->name('order.spent.transaction');

// verify otp
Route::get('verify-otp', [App\Http\Controllers\VerifyOtpController::class, 'showVerifyOtpForm'])->name('auth.verify_otp_form');
Route::post('/verify/email/otp', [App\Http\Controllers\VerifyOtpController::class, 'verifyEmailOtp'])->name('verify.email.otp');
Route::post('/verify/phone/otp', [App\Http\Controllers\VerifyOtpController::class, 'verifyPhoneOtp'])->name('verify.phone.otp');
Route::get('/resend-otp/{type}', [App\Http\Controllers\VerifyOtpController::class, 'resendOtp'])->name('resend.otp');

Route::any('/', [App\Http\Controllers\Customer\ProductController::class, 'index'])->name('index')->middleware('auth_verified');
Route::middleware('localization', 'prevent-back-history')->group(function () {

    Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);
    Route::post('/ajaxlogin', [App\Http\Controllers\Auth\LoginController::class, 'ajaxLogin'])->name('ajaxlogin');
    Route::get('verify-email/{user}/{token}', [App\Http\Controllers\Auth\RegisterController::class, 'verifyEmail'])->name('verify-email');
    Route::any('/verify', [App\Http\Controllers\Auth\ResetPasswordController::class, 'verifyOtp'])->name('verify');
    Route::any('/update-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'update'])->name('user.updatepassword');

    Route::middleware(['web.admin'])->group(function () {
        Route::any('/', [App\Http\Controllers\Customer\ProductController::class, 'index'])->name('index')->middleware('auth_verified');
        Route::get('product/{id}', [App\Http\Controllers\Customer\ProductController::class, 'view'])->name('viewproduct');
    });
    Auth::routes();

    Route::get('retailer/register', function () {
        if (Auth::user()) {
            return redirect()->route('index');
        }

        return view('auth.register');
    })->name('retailer.register');

    Route::get('/lang/{locale?}', function ($locale = null) {
        session()->put('locale', $locale);
        return redirect()->back();
    });



    Route::middleware(['auth', 'VerifyOtp', 'CheckStatus', 'web.admin'])->group(function () {


        // Route::get('products', [App\Http\Controllers\Customer\ProductController::class, 'index'])->name('products');
        Route::get('lender_info/{id}', [App\Http\Controllers\Customer\ProductController::class, 'lenderInfo'])->name('lenderProfile');
        Route::get('product/{product}/book', [App\Http\Controllers\Customer\ProductController::class, 'book'])->name('book');
        Route::get('lender/{id}', [App\Http\Controllers\Customer\ProductController::class, 'retailer'])->name('lender');
        Route::post('transfee', [App\Http\Controllers\Customer\ProductController::class, 'get_transfee'])->name('transfee');
        Route::get('subcat/{id}', [App\Http\Controllers\AjaxController::class, 'get_subcat']);
        Route::get('sub_category/{id}', [App\http\Controllers\AjaxController::class, 'get_subcategory']);
        Route::get('neighborhoodcity/{id}', [App\Http\Controllers\AjaxController::class, 'get_city']);




        Route::post('/report-product/{id}', [App\Http\Controllers\Customer\ProductController::class, 'reportProduct']);


        // add product
        Route::get('/lend', [App\Http\Controllers\Customer\ProductController::class, 'openModel'])->name('open.model');


        Route::post('products', [App\Http\Controllers\Retailer\ProductController::class, 'store'])->name('saveproduct');
        Route::get('products', [App\Http\Controllers\Retailer\ProductController::class, 'index'])->name('product');
        Route::post('store/image', [App\Http\Controllers\Retailer\ProductController::class, 'store_img'])->name('image.store');

        Route::get('delete/image/{token}', [App\Http\Controllers\Retailer\ProductController::class, 'deleteimages'])->name('image.delete');

        Route::middleware('vendorproduct')->group(function () {
            Route::get('products/edit/{id}', [App\Http\Controllers\Retailer\ProductController::class, 'edit'])->name('editproduct');
            Route::post('product/{id}', [App\Http\Controllers\Retailer\ProductController::class, 'update'])->name('updateproduct');
            Route::match(['get', 'post'], 'products/{id}/delete', [App\Http\Controllers\Retailer\ProductController::class, 'destroy'])->name('deleteproduct');
        });

        // cms pages
        Route::get('view/{slug}', [App\Http\Controllers\Customer\CmsController::class, 'cms'])->name('view');
    });

    // card payment
    Route::get('card/details/{query?}/{price?}', [App\Http\Controllers\BookingController::class, 'cardDetail'])->name('card.details');
    Route::post('charge', [App\Http\Controllers\BookingController::class, 'charge'])->name('charge');

    Route::middleware(['auth', 'CheckStatus'])->group(function () {
        //Retailer order
        Route::match(['get', 'post'], 'retailer/order', [App\Http\Controllers\Retailer\OrderController::class, 'index'])->name('retailercustomer');
        Route::get('order/{order}', [App\Http\Controllers\Retailer\OrderController::class, 'viewOrder'])->name('retailervieworder');
        Route::post('retailer/order/{order}/pickup', [App\Http\Controllers\Retailer\OrderController::class, 'orderPickUp'])->name('retailerorderpickup');
        Route::get('retailer/order/{order}/confirm-pick-up', [App\Http\Controllers\Retailer\OrderController::class, 'confirmPickUp'])->name('retailer.confirmpickup');
        Route::post('retailer/order/{order}/return', [App\Http\Controllers\Retailer\OrderController::class, 'orderReturn'])->name('retailerorderreturn');
        Route::post('order1/{order}/dispute', [App\Http\Controllers\Retailer\OrderController::class, 'orderDispute'])->name('retailer.orderdispute');

        Route::get('retailer/order/{order}/confirm-return', [App\Http\Controllers\Retailer\OrderController::class, 'confirmReturn'])->name('retailerconfirmreturn');
        Route::get('retailer/order/{order}/image/{image}/download', [App\Http\Controllers\Retailer\OrderController::class, 'downloadAttachment'])->name('retailer.downloadattachment');
        Route::post('retailer/order/{order}/cancel', [App\Http\Controllers\Retailer\OrderController::class, 'cancelOrder'])->name('retailer-cancel-order');

        // lender image
        // Route::post('retailer/order/pickup', [App\Http\Controllers\Retailer\OrderController::class, 'orderPickUp'])->name('retailerorderpickup');

        Route::post('order/image', [App\Http\Controllers\Retailer\OrderController::class, 'store_img'])->name('orderimage');


        Route::post('payment/approve', [App\Http\Controllers\StripeController::class, 'payment_transaction'])->name('paymentapprove');
        Route::post('reject/order', [App\Http\Controllers\StripeController::class, 'reject_order'])->name('rejectorder');

        // Route::post('order/{order}/return', [App\Http\Controllers\Retailer\OrderController::class, 'orderReturn'])->name('retailer.orderreturn');
        Route::get('image/delete/{token}', [App\Http\Controllers\Retailer\OrderController::class, 'imagedelete'])->name('delete.image');

        // lender chat
        Route::post('retailer/order/{order}/chat', [App\Http\Controllers\Retailer\OrderController::class, 'saveChat'])->name('retalersavechat');
        Route::post('retailer/store/chat', [App\Http\Controllers\ChatController::class, 'storeChat'])->name('retailerstore.chat');
        Route::post('retailer/chat/messages', [App\Http\Controllers\ChatController::class, 'chatMessages'])->name('retailerchat.messages');
        Route::post('retailer/lastchat/update', [App\Http\Controllers\ChatController::class, 'lastchat_update'])->name('retailerlastchat.update');
        Route::post('retailer/getuser/names', [App\Http\Controllers\ChatController::class, 'userNames'])->name('retailergetuser.names');
        Route::get('retailer/userimage', [App\Http\Controllers\ChatController::class, 'userImage'])->name('retaileruserimage');
        Route::post('retailer/chat/image', [App\Http\Controllers\ChatController::class, 'chatImage'])->name('chat.image');
        Route::get('retailer/chat/search/{string}', [App\Http\Controllers\ChatController::class, 'chatSearch'])->name('retailerchat.search');


        Route::post('identification', [App\Http\Controllers\ProfileController::class, 'identification'])->name('identification');


        // bank details
        Route::match(['get', 'post'], 'bankdetail', [App\Http\Controllers\ProfileController::class, 'bankdetail'])->name('bankdetail');


        // country state city routes
        Route::get('states/{country}', [App\Http\Controllers\AjaxController::class, 'states'])->name('states');
        Route::get('cities/{stateId}', [App\Http\Controllers\AjaxController::class, 'cities'])->name('cities');

        Route::any('stripe/onboarding', [StripeOnboardingController::class, 'redirectToStripe'])->name('stripe.onboarding.redirect');
        Route::get('stripe/onboarding/refresh', [StripeOnboardingController::class, 'refreshOnboarding'])->name('stripe.onboarding.refresh');
        Route::get('stripe/onboarding/complete', [StripeOnboardingController::class, 'completeOnboarding'])->name('stripe.onboarding.complete');
    });
    // logged in routes
    Route::middleware(['auth', 'customer', 'CheckStatus'])->group(function () {
        Route::get('retailer/chat/{order}', [App\Http\Controllers\Retailer\OrderController::class, 'orderChat'])->name('retalerorderchat');

        Route::post('add-favorite', [App\Http\Controllers\Customer\ProductController::class, 'addFavorite'])->name('addfavorite');
        Route::get('my-wishlist', [App\Http\Controllers\Customer\ProductController::class, 'wishlist'])->name('wishlist');
        Route::match(['get', 'post'], 'orders', [App\Http\Controllers\Customer\OrderController::class, 'index'])->name('orders');
        Route::get('payment-history', [App\Http\Controllers\Customer\OrderController::class, 'payment_history'])->name('payment-history');

        // rental request here
        Route::get('rental-request', [App\Http\Controllers\Customer\OrderController::class, 'rental_request'])->name('rental-request');
        // my account
        Route::get('my-profile', [App\Http\Controllers\ProfileController::class, 'profile'])->name('profile');
        Route::get('handle', [App\Http\Controllers\ProfileController::class, 'handle'])->name('handle');
        Route::get('edit-account', [App\Http\Controllers\ProfileController::class, 'edit_profile'])->name('edit-account');
        Route::post('change-password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('changepassword');
        Route::post('update-profile', [App\Http\Controllers\ProfileController::class, 'saveUserDetail'])->name('saveprofile');
        Route::get('remove-profile', [App\Http\Controllers\ProfileController::class, 'removeProfile'])->name('removeprofile');
        Route::post('notification-setting', [App\Http\Controllers\ProfileController::class, 'notificationSetting'])->name('notificationsetting');
        Route::get('download-proof', [App\Http\Controllers\ProfileController::class, 'downloadProof'])->name('download-proof');
        Route::get('notifications', [App\Http\Controllers\ProfileController::class, 'notifications'])->name('notifications');
        Route::get('switch-profile/{role}', [App\Http\Controllers\ProfileController::class, 'switch_profile'])->name('switch-profile');

        Route::get('/change_pass', [App\Http\Controllers\ProfileController::class, 'ChangePassword'])->name('user.changePassword');
        Route::get('change-profile/{user}', [App\Http\Controllers\ProfileController::class, 'changeProfile'])->name('change-Profile');
        Route::post('update_profile', [App\Http\Controllers\ProfileController::class, 'saveUserprofile'])->name('saveUserprofile');
        Route::post('update-profile/{type?}', [App\Http\Controllers\ProfileController::class, 'accountSetting'])->name('Userprofile');
        Route::post('notification_preference', [App\Http\Controllers\ProfileController::class, 'notificationPrefrence'])->name('notification_preference');

        // card
        Route::get('card-index', [App\Http\Controllers\CardController::class, 'index'])->name('card.index');
        Route::post('card-store', [App\Http\Controllers\CardController::class, 'store'])->name('card.store');
        Route::post('card-destroy', [App\Http\Controllers\CardController::class, 'destroy'])->name('card.delete');
        Route::post('cards-default', [App\Http\Controllers\CardController::class, 'default'])->name('card.default');

        // product booking and payment
        Route::middleware('verifieduser')->group(function () {
            // checkout page
            Route::post('checkout/{product}', [App\Http\Controllers\StripeController::class, 'checkout'])->name('checkout');
            Route::get('billing/{token}', [App\Http\Controllers\StripeController::class, 'billing'])->name('billing');
            // Route::post('charge', [App\Http\Controllers\StripeController::class, 'charge'])->name('charge');
            Route::get('payment-success', [App\Http\Controllers\StripeController::class, 'success'])->name('paymentsuccess');
            Route::get('payment-failed', [App\Http\Controllers\StripeController::class, 'failed'])->name('paymentfailed');

            Route::get('userimage', [App\Http\Controllers\ChatController::class, 'userImage'])->name('userimage');
            Route::post('store/chat', [App\Http\Controllers\ChatController::class, 'storeChat'])->name('store.chat');
            Route::post('chat/messages', [App\Http\Controllers\ChatController::class, 'chatMessages'])->name('chat.messages');
            Route::post('chat/image', [App\Http\Controllers\ChatController::class, 'chatImage'])->name('chat.image1');
            Route::post('getuser/names', [App\Http\Controllers\ChatController::class, 'userNames'])->name('getuser.names');
            Route::post('lastchat/update', [App\Http\Controllers\ChatController::class, 'lastchat_update'])->name('lastchat.update');
            Route::get('chat/search/{string}', [App\Http\Controllers\ChatController::class, 'chatSearch'])->name('chat.search');
            Route::get('product/review/{orderId}', [App\Http\Controllers\ChatController::class, 'get_review'])->name('product.review');
            // Order route
            Route::middleware('customerorder')->group(function () {
                Route::get('order/{order}/view', [App\Http\Controllers\Customer\OrderController::class, 'viewOrder'])->name('vieworder');
                Route::get('order/{order}/success', [App\Http\Controllers\Customer\OrderController::class, 'orderSuccess'])->name('ordersuccess');
                Route::post('order/{order}/dispute', [App\Http\Controllers\Customer\OrderController::class, 'orderDispute'])->name('orderdispute');
                Route::post('order/{order}/chat', [App\Http\Controllers\Customer\OrderController::class, 'saveChat'])->name('savechat');
                Route::get('order/{order}/chat/{chat}/download', [App\Http\Controllers\Customer\OrderController::class, 'downloadImage'])->name('downloadchatattachment');
                Route::get('order/{order}/confirm-pick-up', [App\Http\Controllers\Customer\OrderController::class, 'confirmPickUp'])->name('confirmpickup');
                Route::get('order/{order}/confirm-return', [App\Http\Controllers\Customer\OrderController::class, 'confirmReturn'])->name('confirmreturn');
                // Route::post('order/{order}/add-review', [App\Http\Controllers\Customer\OrderController::class, 'addReview'])->name('addreview');

                // card payment
                Route::get('card/details/{query?}/{price?}', [App\Http\Controllers\BookingController::class, 'cardDetail'])->name('card.details');

                Route::post('charge', [App\Http\Controllers\BookingController::class, 'charge'])->name('charge');


                //Cancel Order
                Route::post('order/{order}/cancel', [App\Http\Controllers\Customer\OrderController::class, 'cancelOrder'])->name('cancel-order');
                Route::get('order/{order}/image/{image}/download', [App\Http\Controllers\Customer\OrderController::class, 'downloadAttachment'])->name('downloadattachment');
            });
            // image route
            Route::post('order/{order}/pickup', [App\Http\Controllers\Customer\OrderController::class, 'orderPickUp'])->name('orderpickup');
            Route::post('order/{order}/return', [App\Http\Controllers\Customer\OrderController::class, 'orderReturn'])->name('orderreturn');
            Route::post('order/images', [App\Http\Controllers\Customer\OrderController::class, 'store_imges'])->name('customer.orderimage');

            // end

            Route::get('order/{order}/chat', [App\Http\Controllers\Customer\OrderController::class, 'orderChat'])->name('orderchat');

            // Query section code here
            Route::post('query', [App\Http\Controllers\Customer\QueryController::class, 'store'])->name('query');
            Route::get('my_inquiry', [App\Http\Controllers\Customer\QueryController::class, 'myQuery'])->name('my_query');
            Route::get('query_view', [App\Http\Controllers\Customer\QueryController::class, 'view'])->name('query_view');
            Route::get('received_query', [App\Http\Controllers\Customer\QueryController::class, 'receiveQuery'])->name('receive_query');
            Route::get('accept_query/{id}', [App\Http\Controllers\Customer\QueryController::class, 'acceptQuery'])->name('accept_query');
            Route::get('reject_query/{id}', [App\Http\Controllers\Customer\QueryController::class, 'rejectQuery'])->name('reject_query');
            Route::get('/fetch-queries', [App\Http\Controllers\Customer\QueryController::class, 'fetchQueries'])->name('fetch.queries');


            Route::post('order/add-review', [App\Http\Controllers\Customer\OrderController::class, 'addReview'])->name('addreview');
            //common chat
            Route::get('/chat', [App\Http\Controllers\ChatController::class, 'common_chat'])->name('common.chat');


            Route::post('order/add-review', [App\Http\Controllers\Customer\OrderController::class, 'addReview'])->name('addreview');

            Route::get('user/notification', [App\Http\Controllers\Api\ProfileController::class, 'test']);



            Route::post('/send-verification-email', [App\Http\Controllers\StripeIdentityController::class, 'sendVerificationEmail'])->name('send.verification.email');
            Route::get('/verification/success', [App\Http\Controllers\StripeIdentityController::class, 'verificationSuccess'])->name('verification.success');
            Route::post('/webhook/stripe', [App\Http\Controllers\StripeIdentityController::class, 'handleStripeWebhook']);

            Route::post('/address/store', [App\Http\Controllers\ProfileController::class, 'addressStore'])->name('address.store');
            Route::delete('/address/{id}', [App\Http\Controllers\ProfileController::class, 'addressDestroy'])->name('address.destroy');
            Route::get('/fetch-address', [App\Http\Controllers\ProfileController::class, 'fetchAddress'])->name('fetch.address');

        });
    });
});
