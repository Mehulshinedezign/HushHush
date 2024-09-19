<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StripeService; // Make sure to create this service
use Illuminate\Support\Facades\Log;
use App\Models\User; // Ensure you have the User model

class StripeIdentityController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    // Endpoint to create a verification session and send a verification email
    public function createVerificationSession(Request $request)
    {
        $user = auth()->user();

        // Define the return URL after successful Stripe verification
        $returnUrl = route('api.verification.success'); // Update this route as needed

        try {
            // Create a Stripe verification session and pass the return URL
            $session = $this->stripeService->createVerificationSession($user->email, [
                'return_url' => $returnUrl, // Stripe will redirect here upon completion
            ]);

            // Send back session URL to redirect user for verification
            return response()->json([
                'status' => 'success',
                'message' => 'Verification session created.',
                'url' => $session->url, // Stripe verification URL
                'session_id' => $session->id,
            ]);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Endpoint to handle Stripe webhook events
    // public function handleWebhook(Request $request)
    // {
    //     $payload = $request->all();

    //     // Log the webhook payload for debugging
    //     Log::info('Stripe webhook received', ['payload' => $payload]);

    //     // Retrieve event type and data
    //     $event = $payload['type'];
    //     $eventObject = $payload['data']['object'];

    //     switch ($event) {
    //         case 'identity.verification_session.verified':
    //             Log::info('Handling identity.verification_session.verified event', ['type' => $event]);

    //             // Get the user email from metadata
    //             $userEmail = $eventObject['metadata']['email'] ?? null;

    //             if ($userEmail) {
    //                 // Find the user by email and update their verification status
    //                 $user = User::where('email', $userEmail)->first();

    //                 if ($user) {
    //                     $user->identity_verified_at = now();
    //                     $user->save();

    //                     Log::info('User verification status updated', ['email' => $userEmail]);
    //                 } else {
    //                     Log::warning('User not found for verification update', ['email' => $userEmail]);
    //                 }
    //             } else {
    //                 Log::warning('No user email found in verification session metadata');
    //             }
    //             break;

    //         default:
    //             Log::info('Unhandled event type', ['type' => $event]);
    //             break;
    //     }

    //     return response()->json(['status' => 'success']);
    // }

    // Endpoint to handle success redirection (if needed)
    public function verificationSuccess()
    {
        // Redirect to home page or any other page with a success message
        // return redirect()->route('apiIndex')->with('success', 'Your identity verification has been successfully completed.');
        return response()->json(['message' => 'Your identity verification has been successfully completed.'], 200);
    }
}
