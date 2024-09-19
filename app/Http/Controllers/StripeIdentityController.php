<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\VerificationMail;
use Illuminate\Support\Facades\Mail;
use App\Services\StripeService;

class StripeIdentityController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function sendVerificationEmail(Request $request)
    {
        $user = auth()->user();

        $returnUrl = route('verification.success'); // This route handles success after verification

        $session = $this->stripeService->createVerificationSession($user->email, [
            'return_url' => $returnUrl, // Stripe will redirect here upon completion
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Verification email sent.',
            'url' => $session->url, // Stripe verification URL
            'return_url' => $returnUrl, // Send return URL
            'session_id' => $session->id,
        ]);
    }




    public function verificationSuccess()
    {

        session()->flash('showModal1', true);
        return redirect()->route('index')->with('success', 'Your identity verification has been successfully completed.');
    }
}
