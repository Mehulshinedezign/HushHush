<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\SettingMiddleware::class,
        ],

        'api' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used instead of class names to conveniently assign middleware to routes and groups.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'retailer' => \App\Http\Middleware\RetailerMiddleware::class,
        'customer' => \App\Http\Middleware\CustomerMiddleware::class,
        'verifieduser' => \App\Http\Middleware\VerifiedUserMiddleware::class,
        'customerorder' => \App\Http\Middleware\CheckCustomerOrderMiddleware::class,
        'retailerorder' => \App\Http\Middleware\CheckRetailerOrderMiddleware::class,
        'userrole' => \App\Http\Middleware\CheckUserRoleMiddleware::class,
        'vendorproduct' => \App\Http\Middleware\CheckVendorProduct::class,
        'prevent-back-history' => \App\Http\Middleware\PreventBackHistory::class,
        'restrict-admin-retailer' => \App\Http\Middleware\RestrictAdminRetailerMiddleware::class,
        'localization' => \App\Http\Middleware\Localization::class,
        'VerifyOtp' => \App\Http\Middleware\VerifyOtpMiddleware::class,
        'CheckStatus' => \App\Http\Middleware\CheckStatus::class,
        'prevent.admin' => \App\Http\Middleware\PreventAdminAccess::class,
        'web.admin' => \App\Http\Middleware\webadmin::class,
        'auth_verified' => \App\Http\Middleware\RedirectToOtpPage::class,
        // 'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
        'store.guest.redirect.url' => \App\Http\Middleware\StoreGuestRedirectUrl::class,

    ];
    protected $commands = [

        'App\Console\Commands\RegisteredUsers',
        'App\Console\Commands\PickupTime',
        'App\Console\Commands\DropOff',
    ];
}
