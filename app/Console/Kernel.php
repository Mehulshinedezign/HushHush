<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('registered:users')
        //     ->daily();

        // $schedule->command('pickup:users')
        //     ->dailyAt('04:00');

        // $schedule->command('dropoff:user')
        //     ->dailyAt('04:00');

        // $schedule->command('verification:users')
        //     ->daily();
        $schedule->command('query:cancel')
        ->everyTwentySeconds();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
