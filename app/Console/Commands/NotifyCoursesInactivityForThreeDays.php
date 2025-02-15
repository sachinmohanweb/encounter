<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;
use App\Models\Batch;
use App\Models\Course;
use App\Models\UserLMS;
use App\Models\CourseContent;
use App\Models\UserDailyReading;

use Log;
use Carbon\Carbon;


use App\Notifications\NotificationPusher; 
use App\Jobs\SendPushNotification;


class NotifyCoursesInactivityForThreeDays extends Command
{

    protected $signature = 'app:notify-course-inactivity-for-three-days';

    protected $description = 'Send notifications for course inactivity of 3 days';
    
    public function handle()
    {

        $userLms = UserLMS::from(with(new UserLMS)->getTable() . ' as ulms')
            ->join(with(new Batch)->getTable() . ' as b', 'b.id', 'ulms.batch_id')
            ->select('ulms.*', 'b.id as batch_id', 'b.start_date as batch_start_date')
            ->where('ulms.status', 1)
            ->where('ulms.completed_status', '!=', 3)
            ->where('b.status', 1)
            ->whereDate('b.start_date', '<',  now())
            ->where('ulms.user_id',1)
            ->get();

        foreach ($userLms as $value) {
            $user = User::find($value->user_id);
            $userTimeZone = $user->timezone ?? 'UTC';
            $localCurrentTime = Carbon::now($userTimeZone)->format('H:i');
            $desiredTime = '20:00';

            // if ($localCurrentTime !== $desiredTime) {
            //     continue;
            // }

            $courses = Course::from(with(new Course)->getTable() . ' as c')
                ->join(with(new CourseContent)->getTable() . ' as cc', 'c.id', '=', 'cc.course_id')
                ->select('cc.*', 'c.id as course_id')
                ->where('c.id', $value->course_id)
                ->orderBy('cc.day', 'desc')
                ->limit(1)->first();

            if ($courses) {
                $userReadings = UserDailyReading::where('user_lms_id', $value->id)
                    ->orderBy('day', 'desc')
                    ->limit(1)->first();

                $batchDetails = Batch::find($value->batch_id);

                if ($userReadings) {
                    $readingDate = Carbon::parse($userReadings->date_of_reading, 'UTC')
                                    ->setTimezone($userTimeZone);
                    $today = Carbon::now($userTimeZone);

                    if ($today->diffInDays($readingDate) >= 3) {
                        // Notify user inactivity for 3 days
                        $this->sendNotification($value, $batchDetails, 'inactivity', $userTimeZone);
                    }
                } else {
                    $startDate = Carbon::parse($batchDetails->start_date, 'UTC')
                                    ->setTimezone($userTimeZone);
                    $today = Carbon::now($userTimeZone);

                    if ($today->diffInDays($startDate) >= 3) {
                        // Notify user has not started course yet
                        $this->sendNotification($value, $batchDetails, 'not_started', $userTimeZone);
                    }
                }
            }
        }   
    }

    private function sendNotification($userLms, $batchDetails, $type, $userTimeZone)
    {
        $pushData = [];
        $pushData['tokens'] = User::whereNotNull('refresh_token')
            ->where('id', $userLms->user_id)
            ->pluck('refresh_token')
            ->toArray();

        if ($type === 'inactivity') {
            $pushData['title'] = 'We Miss You at batch - ' .$batchDetails->batch_name.' : ' . $batchDetails->course->course_name;
            $pushData['body'] = 'It’s been a few days since you last visited.' . PHP_EOL . 'Jump back in and continue your journey to success!';
        } elseif ($type === 'not_started') {
            $pushData['title'] = 'Your Learning Journey Awaits!';
            $pushData['body'] = 'You’re all set to start course  - ' .$batchDetails->batch_name.' : ' . $batchDetails->course->course_name . '.' . PHP_EOL . 'Get a head start and dive into your first lesson now.';
        }

        $pushData['route'] = 'CourseInactivity';
        $pushData['id'] = $userLms->batch_id;
        $pushData['category'] = 'CourseInactivity';
        $pushData['data1'] = $batchDetails->course->course_name;
        $pushData['data2'] = Carbon::parse($batchDetails->start_date, 'UTC')->setTimezone($userTimeZone)->toDateTimeString();
        $pushData['data3'] = Carbon::parse($batchDetails->last_date, 'UTC')->setTimezone($userTimeZone)->toDateTimeString();
        $pushData['data4'] = null;
        $pushData['data5'] = null;
        $pushData['image1'] = null;

        if (!empty($pushData['tokens'])) {

            if(env('QUEUE_CONNECTION') === 'sync') {
                $pusher = new NotificationPusher();
                $pusher->push($pushData);
            }else{
                Log::channel('notification_log')->info("======>>>>>old Notifications  - ".$pushData."  ======>>>>>\n");
                SendPushNotification::dispatch($pushData)->onQueue('push-notifications');
            }   

            if ($type === 'inactivity') {
                Log::channel('notification_log')->info("======>>>>>Notifications for user inactive in course - ".
                    $userLms->batch_id.'-'. $batchDetails->batch_name.' - '.now().' - User : '.$userLms->user_id."  ======>>>>>\n");
            }else{
                Log::channel('notification_log')->info("======>>>>>Notifications for user who didnt started courses yet.- ".$userLms->batch_id.'-'. $batchDetails->batch_name.' - '.now().' - User : '.$userLms->user_id."  ======>>>>>\n");
            }
        }
    }


}
