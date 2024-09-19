<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Identity\VerificationSession;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret')); // Set your Stripe secret key
    }

    public function createVerificationSession($email, $options = [])
    {
        return VerificationSession::create([
            'type' => 'document', // or other types depending on your use case
            'metadata' => [
                'email' => $email,
            ],
            'return_url' => $options['return_url'] ?? null,
        ]);
    }



}
