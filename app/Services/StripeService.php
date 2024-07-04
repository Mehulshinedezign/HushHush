<?php
namespace App\Services;

use Stripe\Stripe;
use Stripe\Token;
use Stripe\Account;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function verifyBankAccount($accountDetails,$country = 'US', $currency = 'usd')
    {
        try {
            $token = Token::create([
                'bank_account' => [
                    'country' => $country,
                    'currency' => $currency,
                    'account_holder_name' => $accountDetails['account_holder_first_name'] . ' ' . $accountDetails['account_holder_last_name'],
                    // 'account_holder_type' => $accountDetails['account_holder_type'],
                    'routing_number' => $accountDetails['routing_number'],
                    'account_number' => $accountDetails['account_number'],
                ],
            ]);

            return $token;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
