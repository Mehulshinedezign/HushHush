<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    public function resetPassword(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'password' => 'required|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
            }

            $userId = $request->user_id;
            $user = User::find($userId);

            if (Hash::check($request->password, $user->password)) {

                $apiResponse = 'success';
                    $statusCode = 422;
                    $message = 'The new password must be different from the current password.';
                    $response = [
                        'token' => $user->createToken('login')->plainTextToken,
                        'user_id' => $user->id,
                    ];
                    return $this->apiResponse($apiResponse, $statusCode, $message, $response, Null);
                // return response()->json([
                //     'status' => false,
                //     'errors' => ['password' => ['The new password must be different from the current password.']],
                // ], 422);
            }


            $user->password = Hash::make($request->password);
            $user->save();

            $apiResponse = 'success';
                    $statusCode = 200;
                    $message = 'Password reset successfully!';
                    $response = [
                        'token' => $user->createToken('login')->plainTextToken,
                        'user_id' => $user->id,
                    ];
                    return $this->apiResponse($apiResponse, $statusCode, $message, $response, Null);

            // return response()->json([
            //     'status' => true,
            //     'message' => 'Password reset successfully!',
            // ], 200);
        } catch (\Throwable $e) {

            $isVerified = false;
            return $this->apiResponse('error', '500', $e->getMessage(), ['errors' => $e->getMessage()],$isVerified);
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong while reset password.'
            ], 500);
            // return response()->json([
            //     'status' => 'error',
            //     'errors' => ['exception' => [$e->getMessage()]],
            // ], 500);
        }
    }
}
