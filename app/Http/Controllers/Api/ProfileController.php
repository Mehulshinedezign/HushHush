<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserDetail;
use App\Models\UserDocuments;

class ProfileController extends Controller
{
    public function index()
    {
        try {
            $user = auth()->user();
            // dd($user);
            $userData=User::find($user->id, ['id','name', 'email','phone_number','profile_file','profile_url']);

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated',
                    'errors' => []
                ], 401);
            }

            $userDetails = UserDetail::where('user_id', $user->id)->first();
            // $userDocuments = UserDocuments::where('user_id', $user->id)->get();

            $response = [
                'user' => $userData,
                'address' => $userDetails,
                // 'user_documents' => $userDocuments,
            ];

            // dd($response);

            return response()->json([
                'status' => 'success',
                'message' => 'User details retrieved successfully',
                'data' => $response
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => []
            ], 500);
        }
    }
}
