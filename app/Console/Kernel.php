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
//        $schedule->command('message:inbox')->everyMinute();
//        $schedule->command('message:seller-id')->everyMinute();
//        $schedule->command('message:order')->everyMinute();
//        $schedule->command('message:download:image')->everyFiveMinutes();
        //$schedule->command('message:chat')->twiceDaily(1, 13);

        // $schedule->command('inspire')->hourly();
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
