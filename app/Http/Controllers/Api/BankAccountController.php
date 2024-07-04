<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RetailerBankInformation;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BankAccountController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function addOrUpdateBankAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_holder_first_name' => 'required|string',
            'account_holder_last_name' => 'required|string',
            'account_holder_dob' => 'required|date',
          
            'account_number' => 'required|string',
            'routing_number' => 'required|string',
           
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $accountDetails = $request->all();
            $token = $this->stripeService->verifyBankAccount($accountDetails);

            $bankInfo = RetailerBankInformation::updateOrCreate(
                ['retailer_id' => auth()->id()],
                array_merge($accountDetails, [
                    'stripe_btok_token' => $token->id,
                    'is_verified' => true,
                ])
            );

            return response()->json([
                'status' => true,
                'message' => 'Bank account added/updated successfully!',
                'data' => $bankInfo,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'errors' => [],
            ], 500);
        }
    }
}

