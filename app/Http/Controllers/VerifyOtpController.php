<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\LoginController;
use App\Models\User;
use App\Models\UserOtp;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VerifyOtpController extends Controller
{
    public function showVerifyOtpForm(Request $request)
    {
        $userId = $request->session()->get('userId');
        return view('auth.verify_otp',compact('userId'));
        
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ], [
            'otp.required' => 'OTP is required',
            'otp.digits' => 'OTP must be 6 digits',
        ]);

        $userId = $request->session()->get('userId');
        $user = User::findOrFail($userId);

        // $otp = UserOtp::where('user_id', $userId)
        // ->where('otp', $request->otp)
        // ->where('expires_at', '>=', Carbon::now())
        // ->first();
      
        // if ($otp) {
        //     // $user->otp_is_verified = true;
        //     $user->update(["status" => "1","otp_is_verified" => "1"]);
        //     // $user->save();

        //     $otp->status = 1;
        //     $otp->save();

        //     $request->session()->forget('userId');
        //     $loginController = new LoginController();
            
        //     return $loginController->loginUser($user);
        //     // return redirect()->route('login')->with('success', 'Registration successful. A confirmation email has been sent to ' . $user->email . '. Please verify to log in.');
        // }

        $otp = UserOtp::where('user_id', $userId)
        ->where('expires_at', '>=', Carbon::now())
        ->first();
      
        if ($otp) {
            $user->update(["status" => "1","otp_is_verified" => "1"]);

            $otp->status = 1;
            $otp->save();

            $request->session()->forget('userId');
            $loginController = new LoginController();
            
            return $loginController->loginUser($user);
            // return redirect()->route('login')->with('success', 'Registration successful. A confirmation email has been sent to ' . $user->email . '. Please verify to log in.');
        }

        return redirect()->back()->withErrors(['otp' => 'Invalid or expired OTP']);
    }
}
