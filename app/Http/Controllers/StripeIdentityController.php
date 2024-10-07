<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\VerificationMail;
use Illuminate\Support\Facades\Mail;
use App\Services\StripeService;
use Exception;

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

        $returnUrl = route('verification.success');

        try {
            $session = $this->stripeService->createVerificationSession($user->email, [
                'return_url' => $returnUrl,
            ]);

            session()->flash('message', 'Verification email sent successfully. Please check your inbox.');
            session()->flash('alert-type', 'success');

            return response()->json([
                'status' => 'success',
                'message' => 'Verification email sent.',
                'url' => $session->url,
                'return_url' => $returnUrl,
                'session_id' => $session->id,
            ]);
        } catch (Exception $e) {
            session()->flash('message', 'Failed to send verification email. Please try again.');
            session()->flash('alert-type', 'error');
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send verification email.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function verificationSuccess()
    {
        session()->flash('message', 'Your identity verification has been successfully completed.');
        session()->flash('alert-type', 'success');
        if (auth()->user()->identity_verified == 'verified'){
            session()->flash('showModal1', true);
        return redirect()->route('index')->with('success', 'Your identity verification has been successfully completed.');
        }
        elseif(auth()->user()->identity_verified == 'failed')
        {
            session()->flash('showModal4', true);
            return redirect()->route('index')->with('success', 'Your identity verification has been failed please try again.');

        }
        elseif(auth()->user()->identity_verified == 'canceled')
        {
            // session()->flash('showModal4', true);
            return redirect()->route('index')->with('success', 'Your identity verification has been failed please try again.');

        }
        elseif(auth()->user()->identity_verified == 'pending')
        {
            // session()->flash('showModal4', true);
            return redirect()->route('index')->with('success', 'Your identity verification is under process.');

        }
        else{
            return redirect()->route('index')->with('success', 'Your identity verification is under process.');
        }
    }
}
