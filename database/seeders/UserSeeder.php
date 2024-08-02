<?php

namespace Database\Seeders;

use App\Models\EmailOtp;
use App\Models\PhoneOtp;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'role_id' => 1,
            'name' => 'admin',
            'email' => 'nudora@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Shine@123'),
            'remember_token' => Str::random(10),
            'phone_number' => rand(1234567890, 9999999999),
            'status' => '1',
            'otp_is_verified'=>true,
        ];

        $user=User::create($data);

        $emailData=[
            'user_id'=> $user->id,
            'otp'=>'123456',
            'expires_at'=>Carbon::now(),
            'status'=>True,

        ];
        $emailOtp=EmailOtp::updateOrCreate($emailData);

        $phoneData=[
            'user_id'=> $user->id,
            'otp'=>'123456',
            'expires_at'=>Carbon::now(),
            'status'=>True,

        ];
        $phoneOtp=PhoneOtp::updateOrCreate($emailData);




    }
}
