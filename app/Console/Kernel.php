<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected function schedule(Schedule $schedule)
    {

        $schedule->command('app:notify-upcoming-courses-enrollment-left-days-new')->everyMinute();
        $schedule->command('app:notify-course-inactivity-for-three-days-new')->everyMinute();
        $schedule->command('telescope:prune --hours=12')->everyMinute();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
