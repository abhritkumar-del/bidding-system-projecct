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
        // Update auction status every minute
        // This checks for scheduled -> active and active -> ended
        $schedule->command('auction:update-status')
                 ->everyMinute()
                 ->withoutOverlapping()
                 ->runInBackground();

        // Process winning bids every 5 minutes
        // Creates payment records for ended auctions
        $schedule->command('auction:process-winners')
                 ->everyFiveMinutes()
                 ->withoutOverlapping()
                 ->runInBackground();

        // Clean expired scheduled auctions once per hour
        // Cancels auctions that were scheduled but never activated
        $schedule->command('auction:clean-expired')
                 ->hourly()
                 ->withoutOverlapping()
                 ->runInBackground();

        // Optional: Database backup daily at 2 AM
        // Uncomment if you want automatic backups
        // $schedule->command('backup:database')
        //          ->dailyAt('02:00')
        //          ->runInBackground();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}