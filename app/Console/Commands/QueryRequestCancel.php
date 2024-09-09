<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Notifications\RentalCancelorder;
use App\Mail\Query;
use Carbon\Carbon;

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
    protected $description = 'Command description';

    /**
    * Execute the console command.
    */

    public function handle() {
        $nextdayDate = date( 'Y-m-d H:i:s', strtotime( '+1 day' ) );
        // $nextdayDate = date( 'Y-m-d H:i:s' );

        $queries =  DB::table( 'queries' )->where( 'status', 'PENDING' )->get();
        foreach ( $queries as $query ) {
            $user = DB::table( 'users' )->where('id',$query->user_id )->first();
        
            $createdDate = Carbon::parse($query->created_at);
            $twoHoursBeforeRejection = Carbon::now()->subHours(22);  // 22 hours ago (for sending email)
            $oneDayAgo = Carbon::now()->subDay();  // 24 hours ago (for rejection)
        
             // Send email if created_at is older than 22 hours but less than 24 hours
            if ($createdDate->lessThan($twoHoursBeforeRejection) && $createdDate->greaterThan($oneDayAgo)) {
                // Send email
                $user->notify(new QueryRejected());              
                
            }
            // Check if createdDate is more than or equal to 24 hours old (reject the query)
            if ($createdDate->lessThan($oneDayAgo)) {
                DB::table('queries')->where('id', $query->id)->update(['status' => 'REJECTED']);
            }

        }
    }
}
