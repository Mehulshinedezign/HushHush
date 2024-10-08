<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\UserBankDetail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Laravel\Cashier\Events\WebhookReceived;
use Illuminate\Support\Facades\Log;
use App\Notifications\IdentityVerificationStatus;

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

                $userEmail = $verificationSession['metadata']['email'] ?? null;

                if ($userEmail) {
                    $user = User::where('email', $userEmail)->first();

                    if ($user) {
                        $user->identity_verified = 'verified';
                        $user->identity_status = 'unpaid';
                        $user->save();

                        // Send notification to user
                        $user->notify(new IdentityVerificationStatus('verified'));

                        Log::info('User verification status updated and notification sent', ['email' => $userEmail]);
                    } else {
                        Log::warning('User not found for verification update', ['email' => $userEmail]);
                    }
                } else {
                    Log::warning('No user email found in verification session metadata');
                }
                break;

            case 'identity.verification_session.requires_input':
                Log::info('Handling identity.verification_session.requires_input event', ['type' => $event->payload['type']]);
                $verificationSession = $eventObject;

                $userEmail = $verificationSession['metadata']['email'] ?? null;

                if ($userEmail) {
                    $user = User::where('email', $userEmail)->first();

                    if ($user) {
                        $user->identity_verified = 'failed';
                        $user->save();

                        // Send notification to user
                        $user->notify(new IdentityVerificationStatus('failed'));

                        Log::info('User verification status updated and notification sent', ['email' => $userEmail]);
                    } else {
                        Log::warning('User not found for verification update', ['email' => $userEmail]);
                    }
                } else {
                    Log::warning('No user email found in verification session metadata');
                }
                break;

            case 'identity.verification_session.canceled':
                Log::info('Handling identity.verification_session.canceled event', ['type' => $event->payload['type']]);
                $verificationSession = $eventObject;

                $userEmail = $verificationSession['metadata']['email'] ?? null;

                if ($userEmail) {
                    $user = User::where('email', $userEmail)->first();

                    if ($user) {
                        $user->identity_verified = 'canceled';
                        $user->save();

                        // Send notification to user
                        $user->notify(new IdentityVerificationStatus('canceled'));

                        Log::info('User verification status updated and notification sent', ['email' => $userEmail]);
                    } else {
                        Log::warning('User not found for verification update', ['email' => $userEmail]);
                    }
                } else {
                    Log::warning('No user email found in verification session metadata');
                }
                break;

            case 'identity.verification_session.processing':
                Log::info('Handling identity.verification_session.processing event', ['type' => $event->payload['type']]);
                $verificationSession = $eventObject;

                $userEmail = $verificationSession['metadata']['email'] ?? null;

                if ($userEmail) {
                    $user = User::where('email', $userEmail)->first();

                    if ($user) {
                        $user->identity_verified = 'pending';
                        $user->save();

                        // Send notification to user
                        $user->notify(new IdentityVerificationStatus('pending'));

                        Log::info('User verification status updated and notification sent', ['email' => $userEmail]);
                    } else {
                        Log::warning('User not found for verification update', ['email' => $userEmail]);
                    }
                } else {
                    Log::warning('No user email found in verification session metadata');
                }
                break;

            case 'identity.verification_session.unverified':
                Log::info('Handling identity.verification_session.unverified event', ['type' => $event->payload['type']]);
                $verificationSession = $eventObject;

                $userEmail = $verificationSession['metadata']['email'] ?? null;

                if ($userEmail) {
                    $user = User::where('email', $userEmail)->first();

                    if ($user) {
                        $user->identity_verified = 'failed';
                        $user->save();

                        // Send notification to user
                        $user->notify(new IdentityVerificationStatus('failed'));

                        Log::info('User verification status updated and notification sent', ['email' => $userEmail]);
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
