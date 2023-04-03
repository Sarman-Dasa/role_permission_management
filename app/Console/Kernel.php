<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

    protected $commands = [
        commands\AutoBirthDayWish::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('auto:birthdatwish')->cron('* * * * *');
        $schedule->command('auto:birthdatwish')->dailyAt('15:11')->timezone('Asia/Kolkata');
        //$schedule->command('auto:birthdatwish')->dailyAt('14:21');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
        
    }
}
