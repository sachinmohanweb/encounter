<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Batch;
use App\Models\User;
use Log;
use Carbon\Carbon;
use App\Notifications\NotificationPusher;
use App\Jobs\SendUpcomingCourseLastDateNotification;

class NotifyUpcomingCoursesEnrollmentNew extends Command
{
    protected $signature = 'app:notify-upcoming-courses-enrollment-left-days-new';
    protected $description = 'Send notifications for courses starting within 3 days at 10:00 AM user local time';

    public function handle()
    {

        $timezones = User::whereNotNull('timezone')->whereNotNull('refresh_token')
                    //->where('timezone','Asia/Kolkata')
                    ->pluck('timezone')->unique()->toArray();

        foreach ($timezones as $timezone) {
            $timezoneTime = Carbon::now($timezone)->format('H:i');

            if ($timezoneTime === '20:15') {
                if(env('QUEUE_CONNECTION') === 'sync') {
                    $pusher = new NotificationPusher();
                    $pusher->pushBatch($notifications);
                }else{
                    Log::channel('notification_log')->info("Upcoming Course Last Date Notification job calling for : {$timezone}");
                    SendUpcomingCourseLastDateNotification::dispatch($timezone)->onQueue('push-bulk-notifications');
                }
            }
        }
    }
}