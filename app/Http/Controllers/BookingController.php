<?php

namespace App\Http\Controllers;

use App\Models\AdminSetting;
use App\Models\Query;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;

class BookingController extends Controller
{
    public function cardDetail(Query $query = null, $price = null)
    {
        $user = auth()->user();
        $intent = $user->createSetupIntent();
        $insurance =  AdminSetting::where('key', 'insurance_fee')->first();
        $security =  AdminSetting::where('key', 'security_fee')->first();
        return view('customer.card_payment', compact('intent', 'query', 'price', 'insurance', 'security'));
    }

    public function charge(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $user = auth()->user();
        $stripeCustomer = $user->createOrGetStripeCustomer();


        $status = PaymentIntent::create([
            'amount' => floatval($request->total_payment) * 100, // amount in cents
            'currency' => 'usd',
            'customer' => $stripeCustomer->id,
            'payment_method' => $request->token,
            'confirmation_method' => 'manual',
            'confirm' => true,
            // 'description' => jsencode_userdata($cart->id),
            // 'metadata' => [
            //     'cart_id' => jsencode_userdata($cart->id), // Add your custom order ID as metadata
            // ],
            'return_url' => route('index')
        ]);
        if ($status->status == "succeeded") {
            return redirect()->route('index');
        }
    }
}
