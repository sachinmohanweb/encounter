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
use App\Models\SentNotification;

use Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Notifications\NotificationPusher; 

class SendCourseInactivityNotification implements ShouldQueue
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
        $today_string = Carbon::now($timezone)->format('Y-m-d');


        Log::channel('notification_log')->info("Course Inactivity Notification job Dispatched for: {$timezone}");

        $userLmsRecords = UserLMS::where('status', 1)
            ->where('completed_status', '!=', 3)
            ->whereHas('user', function ($query) use ($timezone) {
                $query->where('timezone', $timezone)
                    //->where('id',1)
                    ->whereNotNull('refresh_token');
            })
            ->whereHas('batch', function ($query) use ($today_string) {
                $query->where('status', 1)
                    ->whereDate('start_date', '<', $today_string);
            })
            ->orderBy('id')
            ->with(['user', 'batch:id,start_date,batch_name,last_date'])
            ->get()
            ->unique('batch_id')
            ->values();

        foreach ($userLmsRecords as $userLms) {
            $batch = Batch::find($userLms->batch_id);
            if (!$batch) continue;

            $course = Course::find($userLms->course_id);
            if (!$course) continue;

            $latestContent = CourseContent::where('course_id', $course->id)->orderBy('day', 'desc')->first();
            $userReading = UserDailyReading::where('user_lms_id', $userLms->id)->orderBy('day', 'desc')
                            ->where('status',1)->first();

            $today = Carbon::now($timezone);

            if ($userReading) {
                $readingDate = Carbon::parse($userReading->date_of_reading, 'UTC')->setTimezone($timezone);
                if ($today->diffInDays($readingDate) >= 3) {
                    $notification = $this->prepareNotification($userLms, $batch, $course, 'inactivity', $timezone);
                    $type =1;
                    $type_name ='inactivity';
                }
            }else {
                $startDate = Carbon::parse($batch->start_date, 'UTC')->setTimezone($timezone);
                if ($today->diffInDays($startDate) >= 3) {
                    $notification = $this->prepareNotification($userLms, $batch, $course, 'not_started', $timezone);
                    $type =2;
                    $type_name ='not_started';
                }
            }
            if(!empty($notification)) {

                Log::channel('notification_log')
                    ->info("Notification Pusher called for - " . json_encode($notification, JSON_PRETTY_PRINT)  . "  ======>>>>>\n");

                $pusher = new NotificationPusher();
                $success = $pusher->push($notification);
    
            }
        }
    }

    private function prepareNotification($userLms, $batch, $course, $type, $timezone)
    {
        $tokens = User::whereNotNull('refresh_token')->where('id', $userLms->user_id)->pluck('refresh_token')
                        ->toArray();

        if (empty($tokens)) return null;

        $notification = [
            'tokens' => $tokens,
            'route' => 'CourseInactivity',
            'id' => $userLms->batch_id,
            'category' => 'CourseInactivity',

            'data1' => $userLms->user_id,
            'data2' => $course->course_name,
            'data3' => 'Nill',
            'data4' => 'Nill',
            'data5' => 'Nill',
            'image1' => 'Nill',
            'user' => $userLms->user_id,
        ];

        if ($type === 'inactivity') {
            $notification['title'] = 'We Miss You at ' . $batch->batch_name . ': ' . $course->course_name;
            $notification['body'] = "It’s been a few days since you last visited.\nJump right back in and grow in faith!";

        } elseif ($type === 'not_started') {
            $notification['title'] = 'The Word of God Awaits You!';
            $notification['body'] = "You’re all set to start the course - " . $batch->batch_name . ': ' . $course->course_name . ".\nDive into your first lesson now.";
        }


        return $notification;
    }
}
