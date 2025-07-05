<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\User;
use App\Models\UserLMS;
use App\Models\Batch;
use App\Models\Course;
use App\Models\CourseContent;
use App\Models\UserDailyReading;

use Log;
use Carbon\Carbon;

use App\Notifications\NotificationPusher; 

class SendUpcomingCourseLastDateNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timezone;

    public function __construct(string $timezone)
    {
        $this->timezone = $timezone;
    }

    public function handle(): void
    {
        $timezone = $this->timezone;

        Log::channel('notification_log')->info("Upcoming Course Last Date Notification job Dispatched for: {$timezone}");

        $users = User::where('timezone', $timezone)
                    ->whereNotNull('refresh_token')
                    //->where('id',1)
                    ->get();
        if($users){

            foreach ($users as $user) {

                $batches = Batch::where('status', 1)
                    ->whereBetween('last_date', [
                        Carbon::now('UTC')->setTimezone($timezone)->startOfDay()->timezone('UTC'),
                        Carbon::now('UTC')->setTimezone($timezone)->addDays(2)->endOfDay()->timezone('UTC')
                    ])
                    ->get()
                    ->map(function ($batch) use($timezone){
                        $today = Carbon::now()->setTimezone($timezone)->startOfDay();
                        $lastDate = Carbon::parse($batch->last_date)->endOfDay();
                        $batch->days_left = $today->diffInDays($lastDate) ;
                        return $batch;
                    });

                if($batches->isEmpty()) {
                    continue;
                }

                foreach ($batches as $batch) {
                    $pushData = [
                        'tokens' => [$user->refresh_token],
                        'title' => $batch->days_left === 1
                            ? 'Hurry up! Today is the last day for enrolment – ' . $batch->course->course_name
                            : 'Reminder: Only ' . $batch->days_left . ' Days Left for Enrolment – ' . $batch->course->course_name,
                        'body' => 'Enroll now in our next batch of ' . $batch->course->course_name . ' starting ' . $batch->start_date,
                        'route' => 'NewBatch',
                        'id' => $batch->id,
                        'category' => 'NewBatch',
                        'data1' => $batch->course->course_name,
                        'data2' => $batch->start_date,
                        'data3' => $batch->last_date,
                        'data4' => 'Nil',
                        'data5' => 'Nil',
                        'image1'=> 'Nil',
                        'user' => $user->id,
                    ];

                    Log::channel('notification_log')->info("Notification Pusher  called for - " . json_encode($pushData, JSON_PRETTY_PRINT)  . "  ======>>>>>\n");

                    $pusher = new NotificationPusher();
                    $pusher->push($pushData);
                }
            }
        }
    }

}
