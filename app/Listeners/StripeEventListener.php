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
        $eventObject = $event->payload['data']['object'];

        if (empty($eventObject['id'])) {
            return;
        }

        switch ($event->payload['type']) {
            case 'account.updated':
                $account = $eventObject;

                // Check if the account is fully set up
                if ($account['details_submitted']) {
                    $user = User::where('stripe_account_id', $account['id'])->first();

                    if ($user) {
                        UserBankDetail::updateOrCreate(
                            ['user_id' => $user->id],
                            [
                                'stripe_id' => $user->stripe_id,
                                'country' => $account['country'],
                                'raw_data' => json_encode($account),
                            ]
                        );

                        Log::info('Stripe account details stored', ['account' => $account]);
                    }
                }
                break;

                // Add more cases for other event types if needed
                // case 'invoice.payment_succeeded':
                //     // Handle invoice.payment_succeeded event
                //     break;
        }
    }
}
