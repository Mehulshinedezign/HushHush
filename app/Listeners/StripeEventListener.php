<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\UserBankDetail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Cashier\Events\WebhookReceived;
use Illuminate\Support\Facades\Log;

class StripeEventListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  \Laravel\Cashier\Events\WebhookReceived  $event
     * @return void
     */
    public function handle(WebhookReceived $event)
    {
        Log::info('Listener started', ["event" => $event]);
        $eventObject = $event->payload['data']['object'];

        // Ensure the event object has a valid 'id'
        if (empty($eventObject['id'])) {
            return;
        }

        switch ($event->payload['type']) {

            case 'account.updated':
                Log::info('Handling account.updated event', ['type' => $event->payload['type']]);
                $account = $eventObject;

                // Check if the account details are fully submitted
                if (!empty($account['details_submitted'])) {
                    $user = User::where('stripe_account_id', $account['id'])->first();

                    if ($user) {
                        UserBankDetail::updateOrCreate(
                            ['user_id' => $user->id],
                            [
                                'stripe_id' => $user->stripe_account_id,
                                'country' => $account['country'],
                                'raw_data' => json_encode($account),
                            ]


                        );

                        Log::info('Stripe account details updated', ['account' => $account]);
                    } else {
                        Log::warning('User not found for Stripe account update', ['account_id' => $account['id']]);
                    }
                }
                break;

            case 'identity.verification_session.verified':
                Log::info('Handling identity.verification_session.verified event', ['type' => $event->payload['type']]);
                $verificationSession = $eventObject;

                // Get the user email from metadata
                $userEmail = $verificationSession['metadata']['email'] ?? null;

                if ($userEmail) {
                    // Find the user by email and update their verification status
                    $user = User::where('email', $userEmail)->first();

                    if ($user) {
                        $user->identity_verified_at = now();
                        $user->identity_status ='unpaid';
                        $user->save();


                        Log::info('User verification status updated', ['email' => $userEmail]);
                    } else {
                        Log::warning('User not found for verification update', ['email' => $userEmail]);
                    }
                } else {
                    Log::warning('No user email found in verification session metadata');
                }
                break;


                // return redirect()->back();

                // Add more cases for other event types if needed
                // case 'invoice.payment_succeeded':
                //     // Handle invoice.payment_succeeded event
                //     break;

            default:
                Log::info('Unhandled Stripe event type', ['type' => $event->payload['type']]);
        }

        Log::info('Listener finished');
    }
}
