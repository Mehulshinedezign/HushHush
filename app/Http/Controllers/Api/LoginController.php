<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $apiResponse = 'error';
        $statusCode = '404';
        $message = "Invalid credentials!";
        $response = [];

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
 

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            if (!$user->status){
                return $this->apiResponse('error', 500, 'Your account is deactivated', ['errors' => 'Your account is deactivated']);
            }
            if(!$user->email_verified_at){
                return $this->apiResponse('error', 500, 'Please verify your email', ['errors' => 'Please verify your email']);
            }

            $apiResponse = 'success';
            $statusCode = '200';
            $message = "User login successfully!";
            $response = [
                'token' => $user->createToken('login')->plainTextToken,
                'name' => $user->name,
                'user_id' => $user->id,
            ];
        }

        return $this->apiResponse($apiResponse,  $statusCode, $message, $response);
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            if ($user) {
                $user->tokens()->delete();
                $apiResponse = 'success';
                $statusCode = '200';
                $message = 'User logged out successfully!';
            } else {
                $apiResponse = 'error';
                $statusCode = '401';
                $message = 'Unauthorized';
            }

            return $this->apiResponse($apiResponse, $statusCode, $message);
        } catch (\Throwable $e) {
            return $this->apiResponse('error', '404', $e->getMessage(), ['errors' =>$e->getMessage()]);
        }
    }

}
