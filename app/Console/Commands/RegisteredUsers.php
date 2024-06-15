<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Mail;
use App\Mail\registerusermail;
use Illuminate\Support\Facades\DB;

class RegisteredUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registered:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email of registered users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $nextdayDate = date('Y-m-d', strtotime('+1 day'));
        $totalUsers = DB::table('users')
            ->whereDate('created_at', '=', $nextdayDate)
            ->get();

        foreach ($totalUsers as $user) {
            $product = DB::table('products')->where('user_id', $user->id)->count();
            if ($product == 0) {
                Mail::to($user->email)->send(new registerusermail($user));
            }
        }
    }
}
