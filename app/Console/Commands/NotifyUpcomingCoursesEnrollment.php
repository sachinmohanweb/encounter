<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Batch;
use App\Models\User;
use Log;
use Carbon\Carbon;
use App\Notifications\NotificationPusher;

class NotifyUpcomingCoursesEnrollment extends Command
{
    protected $signature = 'app:notify-upcoming-courses-enrollment-left-days';
    protected $description = 'Send notifications for courses starting within 3 days at 10:00 AM user local time';

    public function handle()
    {

        $currentUtcTime = Carbon::now('UTC');

        $users = User::whereNotNull('refresh_token')->whereNotNull('timezone')->get();

        if ($users->isEmpty()) {
            //Log::channel('notification_log')->info("No users found for notifications at " . now());
            return;
        }

        foreach ($users as $user) {

            $userTimeZone = $user->timezone ?? 'UTC';

            $userCurrentTime = $currentUtcTime->copy()->setTimezone($userTimeZone);

            if ($userCurrentTime->hour === 19 && $userCurrentTime->minute === 00) {

                $batches = Batch::where('status', 1)
                    ->whereBetween('last_date', [
                        Carbon::now('UTC')->setTimezone($userTimeZone)->startOfDay()->timezone('UTC'),
                        Carbon::now('UTC')->setTimezone($userTimeZone)->addDays(2)->endOfDay()->timezone('UTC')
                    ])
                    ->get()
                    ->map(function ($batch) {
                        $today = Carbon::now()->startOfDay();
                        $lastDate = Carbon::parse($batch->last_date)->endOfDay();
                        $batch->days_left = $today->diffInDays($lastDate)+1 ;
                        return $batch;
                    });

                if ($batches->isEmpty()) {
                    //Log::channel('notification_log')->info("No upcoming batches for user ID {$user->id}.");
                    continue;
                }

                foreach ($batches as $batch) {
                    $pushData = [
                        'tokens' => [$user->refresh_token],
                        'title' => $batch->days_left === 1
                            ? 'Hurry up! Today is the last day for enrollment – ' . $batch->course->course_name
                            : 'Reminder: Only ' . $batch->days_left . ' Days Left for Enrollment – ' . $batch->course->course_name,
                        'body' => 'Enroll now in our next batch of ' . $batch->course->course_name . ' starting ' . $batch->start_date .
                            '. Last date for enrollment: ' . $batch->last_date . '. Don’t miss out',
                        'route' => 'NewBatch',
                        'id' => $batch->id,
                        'category' => 'NewBatch',
                        'data1' => $batch->course->course_name,
                        'data2' => $batch->start_date,
                        'data3' => $batch->last_date,
                        'data4' => null,
                        'data5' => null,
                        'image1' => null
                    ];

                    $pusher = new NotificationPusher();
                    $pusher->push($pushData);

                    Log::channel('notification_log')->info("Notification sent to user ID {$user->id} at " . now() . ". Data: " . json_encode($pushData));
                }
            }
        }
    }
}