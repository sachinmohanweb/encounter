<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;
use App\Models\UserLMS;

use Log;
use Carbon\Carbon;


use App\Notifications\NotificationPusher; 
use App\Jobs\SendCourseInactivityNotification;

class NotifyCoursesInactivityForThreeDaysNew extends Command
{

    protected $signature = 'app:notify-course-inactivity-for-three-days-new';

    protected $description = 'Send notifications for course inactivity of 3 days';
    
    public function handle()
    {
        $timezones = User::whereHas('userLms', function ($query) {
                        $query->where('status', 1);
                    })
                    ->whereNotNull('timezone')->whereNotNull('refresh_token')
                    //->where('timezone','Asia/Kolkata')
                    ->pluck('timezone')->unique()->toArray();

        foreach ($timezones as $timezone) {
            $timezoneTime = Carbon::now($timezone)->format('H:i');

            if ($timezoneTime === '20:00') {
                if(env('QUEUE_CONNECTION') === 'sync') {
                    $pusher = new NotificationPusher();
                    $pusher->pushBatch($notifications);
                }else{
                    Log::channel('notification_log')->info("Course Inactivity Notification job calling for : {$timezone}");
                    SendCourseInactivityNotification::dispatch($timezone)->onQueue('push-bulk-notifications');
                }
            }
        }
    }
}
