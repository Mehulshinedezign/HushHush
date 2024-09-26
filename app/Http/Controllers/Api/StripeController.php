<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductDisableDate;
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
use App\Models\User;
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
            'paymentIntentId' => 'required|string', // Expect the paymentIntent ID from the frontend
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
            // Fetch the PaymentIntent from Stripe using the provided paymentIntentId
            $paymentIntent = PaymentIntent::retrieve($request->paymentIntentId);

            // Retrieve the amount and currency directly from the PaymentIntent object
            $intentAmount = $paymentIntent->amount / 100; // Stripe stores amounts in cents, convert to main currency unit
            $intentCurrency = $paymentIntent->currency;

            // Calculate the total amount based on the query
            $price = $query->negotiate_price ?? $query->getCalculatedPrice($query->date_range);
            $completeAmount = $price + $query->cleaning_charges + $query->shipping_charges;

            if ($paymentIntent->status == 'succeeded') {
                // Parse the query's date range
                $fromDateTime = Carbon::parse($query->from_date);
                $toDateTime = Carbon::parse($query->to_date);

                // Create an order for the query
                $order = Order::create([
                    'user_id' => $query->user_id,
                    'retailer_id' => $query->for_user,
                    'transaction_id' => null, // Temporary, will update after transaction creation
                    'product_id' => $query->product_id,
                    'query_id' => $query->id,
                    'from_date' => $fromDateTime->toDateString(),
                    'to_date' => $toDateTime->toDateString(),
                    'order_date' => now(),
                    'status' => 'Waiting',
                    'total' => $intentAmount,
                    'currency' => strtoupper($intentCurrency), // Store the currency in uppercase
                ]);

                $query->update(['status' => 'COMPLETED']);

                // $user->identity_verified_at = now();
                $user->identity_status ='paid';
                $user->save();







                // Create disable dates for the product based on the query's date range
                $dateRange = explode(' - ', $query->date_range);
                $startDate = Carbon::parse($dateRange[0]);
                $endDate = Carbon::parse($dateRange[1]);

                // Loop through each date within the date range and store in ProductDisableDate
                while ($startDate <= $endDate) {
                    ProductDisableDate::create([
                        'product_id' => $query->product_id,
                        'disable_date' => $startDate->toDateString(),
                    ]);
                    $startDate->addDay();
                }

                // Create a transaction record
                $transaction = Transaction::create([
                    'payment_id' => $paymentIntent->id,
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                    'payment_method' => $paymentIntent->payment_method_types[0],
                    'total' => $intentAmount,
                    'currency' => strtoupper($intentCurrency), // Store currency
                    'date' => now(),
                    'status' => $paymentIntent->status,
                    'gateway_response' => json_encode($paymentIntent),
                ]);

                // Update the order with the correct transaction ID
                $order->update(['transaction_id' => $transaction->id]);
                $forUser = User::where('id', $query->for_user)->with('usernotification', 'pushToken')->first();

                if ($forUser && $forUser->usernotification && $forUser->usernotification->order_req == '1') {
                    $payload['id'] = $order->id;
                    $payload['content'] = "Order Place succesfully";
                    $payload['role'] = 'lender';
                    $payload['type'] = 'inquiry';

                    if ($forUser->pushToken) {
                        sendPushNotifications($forUser->pushToken->fcm_token, $payload);
                    }
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Payment processed and order created successfully',
                    'order' => $order,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Payment failed on Stripe',
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
