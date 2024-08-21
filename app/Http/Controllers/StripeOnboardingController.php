<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Account;
use Stripe\AccountLink;
use App\Models\UserBankDetail; // Make sure to import your UserBankDetail model
use Illuminate\Support\Facades\Log;
use Stripe\Customer;

class StripeOnboardingController extends Controller
{
    /**
     * Redirect to Stripe's onboarding page for connected accounts.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToStripe()
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $account = Account::create([
            'type' => 'express',
        ]);

        auth()->user()->update(['stripe_account_id' => $account->id]);

        $accountLink = AccountLink::create([
            'account' => $account->id,
            'refresh_url' => route('stripe.onboarding.refresh'),
            'return_url' => route('stripe.onboarding.complete'),
            'type' => 'account_onboarding',
        ]);

        // Redirect to Stripe's hosted onboarding page
        return redirect($accountLink->url);
    }

    /**
     * Handle redirect after a failed onboarding process.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function refreshOnboarding()
    {
        return redirect()->route('stripe.onboarding.redirect');
    }

    /**
     * Handle redirect after a successful onboarding process.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    // public function completeOnboarding()
    // {
    //     $user = Auth::user();

    //     Stripe::setApiKey(env('STRIPE_SECRET'));
    //     $account = Account::retrieve($user->stripe_id);

    //     // Store the bank details in the database
    //     UserBankDetail::create([
    //         'user_id' => $user->id,
    //         'stripe_id' => $user->stripe_id,
    //         'country' => $account->country,
    //         'raw_data' => json_encode($account),
    //     ]);

    //     return redirect()->back()->with('success', 'Your account is created and your bank details have been stored.');
    // }
    public function completeOnboarding()
    {
        $user = Auth::user();
        Stripe::setApiKey(config('services.stripe.secret'));

        $account = Account::retrieve($user->stripe_account_id);

        // Check if the account is fully set up
        if ($account->details_submitted) {
            // Store the bank details in the database
            UserBankDetail::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'stripe_id' => $user->stripe_account_id,
                    'country' => $account->country,
                    'raw_data' => json_encode($account),
                ]
            );


            // session()->flash('showModal', true);

            return redirect()->route('index')->with('success', 'Your account is created and your bank details have been stored.');
        } else {
            return redirect()->route('index')->with('error', 'Your Stripe account setup is incomplete. Please complete the onboarding process.');
        }
    }

    public function transaction()
    {
        $orders = Order::with('product', 'retailer.vendorPayout', 'transaction')->where('user_id', auth()->id())->get();
        return view('transaction-history', compact('orders'));
    }

    public function earningTransaction()
    {
        $orders = Order::with('product', 'retailer.vendorPayout', 'transaction')->where('retailer_id', auth()->id())->get();
        // dd($orders);
        return view('earning-transaction', compact('orders'));
    }
}
