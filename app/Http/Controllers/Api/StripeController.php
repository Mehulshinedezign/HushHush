<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Account;
use Stripe\AccountLink;
use App\Models\UserBankDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\PaymentIntent;
use App\Models\Order;
use App\Models\Query;
use App\Models\Transaction;
use Carbon\Carbon;
use Exception;

class StripeController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createCharge(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'currency' => 'required|string',
            'source' => 'required|string',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $charge = Charge::create([
                'amount' => $request->amount,
                'currency' => $request->currency,
                'source' => $request->source,
                'description' => $request->description,
            ]);

            return response()->json(['status' => true, 'data' => $charge], 200);
        } catch (\Exception $e) {
            Log::error('Stripe charge creation failed', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function redirectToStripe()
    {
        try {

            $account = Account::create([
                'type' => 'express',
            ]);

            $user = Auth::user();
            $user->stripe_account_id = $account->id;
            $user->save();

            $accountLink = AccountLink::create([
                'account' => $account->id,
                'refresh_url' => route('api.stripe.onboarding.refresh'),
                'return_url' => route('api.stripe.onboarding.complete'),
                'type' => 'account_onboarding',
            ]);

            return response()->json(['url' => $accountLink->url], 200);
        } catch (\Exception $e) {
            Log::error('', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }



    public function refreshOnboarding()
    {
        return response()->json(['url' => route('api.stripe.onboarding.redirect')], 200);
    }

    public function completeOnboarding()
    {
        return response()->json(['message' => 'Stripe onboarding complete.'], 200);
    }


    public function createPaymentIntent(Request $request, $id)
    {
        // dd('here');
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'currency' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $query = Query::find($id);

            if (!$query) {
                return response()->json([
                    'status' => false,
                    'message' => 'Query not found',
                ], 404);
            }
            $stripeCustomer = $user->createOrGetStripeCustomer();
            $paymentIntent = PaymentIntent::create([
                'amount' => $request->amount * 100,
                'currency' => $request->currency,
                'payment_method' => $request->payment_method,
                'confirm' => true,
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never',
                ],
            ]);
            // dd($paymentIntent);

            if ($paymentIntent->status == 'succeeded') {
                $fromDateTime = Carbon::parse($query->from_date);
                $toDateTime = Carbon::parse($query->to_date);
                $orderStatus = 'COMPLETED';

                $order = Order::create([
                    'user_id' => $query->user_id,
                    'transaction_id' => null,
                    'product_id' => $query->product_id,
                    'query_id' => $query->id,
                    'from_date' => $fromDateTime->toDateString(),
                    'to_date' => $toDateTime->toDateString(),
                    'from_hour' => $fromDateTime->format('H'),
                    'from_minute' => $fromDateTime->format('i'),
                    'to_hour' => $toDateTime->format('H'),
                    'to_minute' => $toDateTime->format('i'),
                    'order_date' => now()->toDateString(),
                    'status' => $orderStatus,
                ]);

                $query->update(['status' => 'COMPLETED']);

                $transaction = Transaction::create([
                    'payment_id' => $paymentIntent->id,
                    'order_id' => $order->id,
                    'user_id' => auth()->user()->id,
                    'payment_method' => $paymentIntent->payment_method_types[0],
                    'total' => $paymentIntent->amount,
                    'date' => now(),
                    'status' => $paymentIntent->status,
                    'gateway_response' => json_encode($paymentIntent),
                ]);

                // Update the order with the correct transaction ID
                $order->update(['transaction_id' => $transaction->id]);

                return response()->json(['status' => true, 'message' => 'Payment successful', 'order' => $order], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'Payment failed', 'paymentIntent' => $paymentIntent], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function createIntent(Request $request)
    {
        try {
            $user = auth()->user();
            $stripeCustomer = $user->createOrGetStripeCustomer();
            $intent = $user->createSetupIntent();
            return response()->json(['status' => true, 'message' => 'Intent Created Succesfully', 'intent' => $intent], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
