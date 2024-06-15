<?php

namespace App\Console\Commands;

use App\Models\NotificationSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class VerificationUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verification:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $Users = DB::table('users')
            ->where('email_verified_at', '=', null)
            ->get();

        $expiry  = Carbon::now()->subMinutes(15);
        foreach ($Users as $user) {
            if ($user->created_at <= $expiry) {
                NotificationSetting::where('user_id', $user->id)->delete();
                DB::table('users')->where('id', $user->id)->delete();
            }
        }
    }
}
