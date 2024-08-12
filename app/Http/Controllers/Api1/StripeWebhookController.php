<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\UserBankDetail;
use App\Models\User;

class StripeWebhookController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $endpointSecret
            );

            // Handle the event
            if ($event->type == 'account.updated') {
                $account = $event->data->object;

                // Check if the account is fully set up
                if ($account->details_submitted) {
                    $user = User::where('stripe_id', $account->id)->first();

                    if ($user) {
                        UserBankDetail::updateOrCreate(
                            ['user_id' => $user->id],
                            [
                                'stripe_id' => $user->stripe_id,
                                'country' => $account->country,
                                'raw_data' => json_encode($account),
                            ]
                        );

                        Log::info('Stripe account details stored', ['account' => $account]);
                    }
                }
            }

            return response()->json(['status' => 'success'], 200);
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['status' => 'invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['status' => 'invalid signature', 'error' => $e->getMessage()], 400);
        }
    }
}
