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
        $user = auth()->user();

        // Validate the request input
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'currency' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        // Retrieve the query associated with the ID
        $query = Query::find($id);

        if (!$query) {
            return response()->json([
                'status' => false,
                'message' => 'Query not found',
            ], 404);
        }

        try {
            // Calculate total amount
            $price = $query->negotiate_price ?? $query->getCalculatedPrice($query->date_range);
            $complete_amount = $price + $query->cleaning_charges + $query->shipping_charges;
            // dd($complete_amount);

            if ($complete_amount != $request->amount) {
                return response()->json([
                    'status' => false,
                    'message' => 'Payment amount mismatch',
                ], 400);
            }

            // Create or retrieve Stripe customer for the user
            $stripeCustomer = $user->createOrGetStripeCustomer();

            // Create the PaymentIntent with Stripe
            $paymentIntent = PaymentIntent::create([
                'amount' => $request->amount * 100, // Stripe expects amount in cents
                'currency' => $request->currency,
                'payment_method' => $request->payment_method,
                'confirm' => true,
                'customer' => $stripeCustomer->id, // Attach the Stripe customer ID
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never',
                ],
            ]);

            // Check if the payment succeeded
            if ($paymentIntent->status == 'succeeded') {
                // Parse date and time from the query
                $fromDateTime = Carbon::parse($query->from_date);
                $toDateTime = Carbon::parse($query->to_date);

                // Create an order for the query
                $order = Order::create([
                    'user_id' => $query->user_id,
                    'retailer_id' => $query->for_user,
                    'transaction_id' => null, // Temporary, will be updated after transaction creation
                    'product_id' => $query->product_id,
                    'query_id' => $query->id,
                    'from_date' => $fromDateTime->toDateString(),
                    'to_date' => $toDateTime->toDateString(),
                    'order_date' => now(),
                    'status' => 'Waiting',
                    'total' => $request->amount,
                ]);

                // Mark the query as completed
                $query->update(['status' => 'COMPLETED']);

                // Record the transaction
                $transaction = Transaction::create([
                    'payment_id' => $paymentIntent->id,
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                    'payment_method' => $paymentIntent->payment_method_types[0],
                    'total' => $paymentIntent->amount,
                    'date' => now(),
                    'status' => $paymentIntent->status,
                    'gateway_response' => json_encode($paymentIntent),
                ]);

                // Update the order with the correct transaction ID
                $order->update(['transaction_id' => $transaction->id]);

                return response()->json([
                    'status' => true,
                    'message' => 'Payment successful',
                    'order' => $order
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Payment failed',
                    'paymentIntent' => $paymentIntent,
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function createIntent(Request $request)
    {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.5',  // Stripe minimum is $0.50 USD
            'currency' => 'required|string|size:3',  // Standard currency code size is 3 (e.g., USD, EUR)
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()], 400);
        }

        try {
            // Create a payment intent in Stripe
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $request->amount * 100,  // Convert amount to cents
                'currency' => strtolower($request->currency),  // Stripe expects lowercase currency codes
                'payment_method_types' => ['card'],  // Stripe uses 'card', not 'cards'
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Payment Intent Created Successfully',
                'intent' => $paymentIntent,
            ], 200);
        } catch (\Stripe\Exception\CardException $e) {
            // Handle card-related errors from Stripe
            return response()->json(['status' => false, 'message' => 'Card Error: ' . $e->getError()->message], 400);
        } catch (\Stripe\Exception\RateLimitException $e) {
            // Handle rate limit errors
            return response()->json(['status' => false, 'message' => 'Rate Limit Error: ' . $e->getError()->message], 429);
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Handle invalid request errors from Stripe
            return response()->json(['status' => false, 'message' => 'Invalid Request: ' . $e->getError()->message], 400);
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            // Handle network communication errors with Stripe
            return response()->json(['status' => false, 'message' => 'Network Error: ' . $e->getError()->message], 500);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Handle general Stripe API errors
            return response()->json(['status' => false, 'message' => 'Stripe API Error: ' . $e->getError()->message], 500);
        } catch (\Exception $e) {
            // Handle any other non-Stripe-related exceptions
            return response()->json(['status' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
