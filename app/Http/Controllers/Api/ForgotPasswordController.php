<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\ForgotPasswordController as MainforgotPassword;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function resetPassword(Request $request){
        
        $forgot = new MainforgotPassword();
        $forgot->sendResetPasswordLinkEmail($request);

        $errors = session('errors');

        if ($errors && $errors->has('email')) {
            $errorMessage = $errors->first('email');
            $apiResponse = 'error';
            $statusCode = '404';
            $message = $errorMessage;
        } else {
            $apiResponse = 'success';
            $statusCode = '200';
            $message = "Please check your email for a password reset link.";    
        }

        return $this->apiResponse($apiResponse,$statusCode,$message);
        
    }    
}
