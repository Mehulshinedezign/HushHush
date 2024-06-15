<?php

namespace App\Providers;

use App\Models\CmsPage;
use Illuminate\Support\ServiceProvider;
use App\Models\Notification;
use App\Models\Setting;
use App\Services\OtpService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(OtpService::class, function ($app) {
            return new OtpService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.bootstrap-4');
        // pass the notifications variable to each view
        view()->composer('*', function ($view) {
            $settings = Setting::all();
            foreach ($settings as $setting) {
                $view->with($setting->key, $setting->value);
            }

            if (Auth::check()) {
                $notifications = Notification::with('order.item.product.thumbnailImage', 'sender')->where('receiver_id', auth()->user()->id)->where('is_read', 0)->orderByDesc('id')->take(5)->get();
                $view->with('notifications', $notifications);
            } else {
                $view->with('notifications', []);
            }

            // if ($this->isMobileDevice()) {
            //     $view->with('isMobile', 'yes');
            // } else {
            //     $view->with('isMobile', 'no');
            // }
            $cms = CmsPage::where('status', 1)->get();
            $view->with('cms', $cms);
        });
    }

    // private function isMobileDevice()
    // {
    //     return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    // }
}
