<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Notifications\RentalCancelorder;
use App\Mail\Query;
use Carbon\Carbon;
use App\Notifications\QueryRejected;
use App\Models\User; // Import the User model
use Illuminate\Support\Facades\Log;

class QueryRequestCancel extends Command {
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'query:cancel';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Command to reject pending queries after 24 hours';

    /**
    * Execute the console command.
    */

    public function handle() {
        $nextdayDate = date('Y-m-d H:i:s', strtotime('+1 day'));
        // Log::info('start', [$nextdayDate]);

        $queries = DB::table('queries')->whereIn('status', ['PENDING', 'ACCEPTED'])->get();
        
        foreach ($queries as $query) {
            // Use the Eloquent model to get the user
            $user = User::find($query->user_id);
            $lender = User::find($query->for_user);

            if (!$user) {
                // Log::warning("User not found for query ID: {$query->id}");
                continue;
            }
            
            $createdDate = Carbon::parse($query->created_at);
            $updatedDate = Carbon::parse($query->updated_at);
            $twoHoursBeforeRejection = Carbon::now()->subHours(22);
            $oneDayAgo = Carbon::now()->subDay();
            
            // Log::info("User", [$user]);
            
            // Send email if created_at is older than 22 hours but less than 24 hours
            if ($createdDate->diffInHours(Carbon::now()) >= 22 && $query->status=='PENDING') {
                // Log::info("Mail create", [$twoHoursBeforeRejection, $oneDayAgo]);
                $lender->notify(new QueryRejected());
            }
            if ($updatedDate->diffInHours(Carbon::now()) >= 22 && $query->status=='ACCEPTED') {
                // Log::info("Mail update", [$twoHoursBeforeRejection, $oneDayAgo]);
                $user->notify(new QueryRejected());
            }
            Log::info('id', [$query->id]);

            // Check if createdDate is more than or equal to 24 hours old (reject the query)
            if ($createdDate->lessThan($oneDayAgo) || $updatedDate->lessThan($oneDayAgo)) {
                // log::info('update status');
                DB::table('queries')->where('id', $query->id)->update(['status' => 'REJECTED']);
            }
           
            // Log::info('dbjvvj');
        }
    }
}
