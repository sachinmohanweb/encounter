<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected function schedule(Schedule $schedule)
    {

        $schedule->command('app:notify-upcoming-courses-enrollment-left-days')->dailyAt('04:30');
        $schedule->command('app:notify-course-inactivity-for-three-days')->dailyAt('05:30');
        //$schedule->command('app:notify-upcoming-courses-enrollment-left-days')->everyMinute();
        //$schedule->command('app:notify-course-inactivity-for-three-days')->everyMinute();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
