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
use Carbon\Carbon;

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
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $account = Account::create([
                'type' => 'express',
            ]);

            $user = Auth::user();
            $user->stripe_id = $account->id;
            $user->save();

            $accountLink = AccountLink::create([
                'account' => $account->id,
                'refresh_url' => route('api.stripe.onboarding.refresh'),
                'return_url' => route('api.stripe.onboarding.complete'),
                'type' => 'account_onboarding',
            ]);

            Log::info('Stripe account created', ['account' => $account]);

            return response()->json(['url' => $accountLink->url], 200);
        } catch (\Exception $e) {
            Log::error('Stripe account creation failed', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function refreshOnboarding()
    {
        return response()->json(['url' => route('api.stripe.onboarding.redirect')], 200);
    }

    public function completeOnboarding()
    {
        try {
            $user = Auth::user();

            Stripe::setApiKey(env('STRIPE_SECRET'));
            $account = Account::retrieve($user->stripe_id);

            if ($account->details_submitted) {
                UserBankDetail::updateOrCreate([
                    'user_id' => $user->id,
                    'stripe_id' => $user->stripe_id,
                    'country' => $account->country,
                    'raw_data' => json_encode($account),
                ]);

                Log::info('Stripe account details stored', ['account' => $account]);

                return response()->json(['message' => 'Your account is created and your bank details have been stored.'], 200);
            } else {
                Log::warning('Stripe account setup incomplete', ['account' => $account]);
                return response()->json(['error' => 'Your Stripe account setup is incomplete. Please complete the onboarding process.'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Stripe account retrieval failed', ['error' => $e->getMessage()]);
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function createPaymentIntent(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'currency' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        try {

            $query = Query::find($id);

            if (!$query) {
                return response()->json([
                    'status' => false,
                    'message' => 'Query not found',
                ], 404);
            }

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $paymentIntent = PaymentIntent::create([
                'amount' => $request->amount * 100,
                'currency' => $request->currency,
                'payment_method' => $request->payment_method,
                'confirmation_method' => 'manual',
                'confirm' => true,
            ]);

            if ($paymentIntent->status == 'succeeded') {
                $fromDateTime = Carbon::parse($query->from_date);
                $toDateTime = Carbon::parse($query->to_date);
                $transactionId = $paymentIntent->id;
                $orderStatus = 'COMPLETED';

                $order = Order::create([
                    'user_id' => $query->user_id,
                    'location_id' => $query->product_id,
                    'transaction_id' => $transactionId,
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

                return response()->json(['status' => true, 'message' => 'Payment successful', 'order' => $order], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'Payment failed', 'paymentIntent' => $paymentIntent], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
