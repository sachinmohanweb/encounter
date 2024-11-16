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


class NotifyCoursesInactivityForThreeDays extends Command
{

    protected $signature = 'app:notify-course-inactivity-for-three-days';

    protected $description = 'Send notifications for course inactivity of 3 days';
    
    public function handle()
    {

        $user_lms = UserLMS::from(with(new UserLMS)->getTable() . ' as ulms')
                ->join(with(new Batch)->getTable() . ' as b', 'b.id', 'ulms.batch_id')
                ->select('ulms.*','b.id as batch_id','b.start_date as batch_start_date')
                ->where('ulms.status', 1)
                ->where('ulms.completed_status','!=',3)
                ->where('b.status', 1)
                ->whereDate('b.start_date', '<', now())
                ->get();

        foreach($user_lms as $key=>$value){

            $courses = Course::from(with(new Course)->getTable() . ' as c')
                ->join(with(new CourseContent)->getTable() . ' as cc', 'c.id', '=', 'cc.course_id')
                ->select('cc.*', 'c.id as course_id')
                ->where('c.id',$value->course_id)
                ->orderBy('cc.day', 'desc')
                ->limit(1)->first();

            if($courses){
                $user_readings = UserDailyReading::where('user_lms_id',$value->id)
                                ->orderBy('day', 'desc')
                                ->limit(1)->first();

                $batch_details = Batch::find($value->batch_id);

                if($user_readings){

                    if($user_readings->day < $courses->day) {
                        $readingDate = Carbon::parse($user_readings->date_of_reading);
                        $today = Carbon::now();
                        if ($today->diffInDays($readingDate) >= 3) {

                            // notify you didnt read course for past  3 days pleadse continue

                            $push_data              = []; 
                            $push_data['tokens']    =  User::whereNotNull('refresh_token')
                                                       ->where('id',$value->user_id)
                                                       //->where('id',1)
                                                       ->pluck('refresh_token')->toArray();

                            $push_data['title']     =   'We Miss You at course - '.$batch_details->course->course_name;

                            $push_data['body']      =   'Its been a few days since you last visited.'.PHP_EOL.' Jump back in and continue your journey to success!';

                            $push_data['route']         =   'CourseInactivity';
                            $push_data['id']            =   $value->batch_id;
                            $push_data['category']      =   'CourseInactivity';

                            $push_data['data1']         =   $batch_details->course->course_name;
                            $push_data['data2']         =   $batch_details->start_date;
                            $push_data['data3']         =   $batch_details->last_date;
                            $push_data['data4']         =   null;
                            $push_data['data5']         =   null;
                            $push_data['image1']        =   null;

                            $pusher = new NotificationPusher();
                            $pusher->push($push_data);

                            Log::channel('notification_log')->info("======>>>>>Notifications for user inactive in course ======>>>>>\n" . json_encode($push_data['tokens']));

                        }
                    }

                }else{
                    $batch = Batch::where('id',$value->batch_id)->first();
                    $startDate = Carbon::parse($batch->start_date);
                    $today = Carbon::now();
                    if ($today->diffInDays($startDate) >= 3) {

                        // notify didnt satrted course yet

                        $push_data              = []; 
                        $push_data['tokens']    =  User::whereNotNull('refresh_token')
                                                   ->where('id',$value->user_id)
                                                   ->pluck('refresh_token')->toArray();

                        $push_data['title']     =   'Your Learning Journey Awaits!';

                        $push_data['body']      =   'You’re all set to start course '.$batch_details->course->course_name.'.'.PHP_EOL.' Get a head start and dive into your first lesson now.';

                        $push_data['route']         =   'CourseInactivity';
                        $push_data['id']            =   $value->batch_id;
                        $push_data['category']      =   'CourseInactivity';

                        $push_data['data1']         =   $batch_details->course->course_name;
                        $push_data['data2']         =   $batch_details->start_date;
                        $push_data['data3']         =   $batch_details->last_date;
                        $push_data['data4']         =   null;
                        $push_data['data5']         =   null;
                        $push_data['image1']        =   null;

                        $pusher = new NotificationPusher();
                        $pusher->push($push_data);

                        Log::channel('notification_log')->info("======>>>>>Notifications for user who didnt started courses yet. ======>>>>>\n" . json_encode($push_data['tokens']));

                    }                    
                }
            }
                
        }

    }
}
