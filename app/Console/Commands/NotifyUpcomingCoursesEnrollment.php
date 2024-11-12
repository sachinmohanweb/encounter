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

    protected $description = 'Send notifications for courses starting within 3 days';
    
    public function handle()
    {
        $batches = Batch::where('status', 1)
                ->whereBetween('last_date', [Carbon::now()->startOfDay(), Carbon::now()->addDays(2)->endOfDay()])
                ->get()
                ->map(function ($batch) {

                    $today = Carbon::now()->startOfDay(); 
                    $lastDate = Carbon::parse($batch->last_date)->endOfDay(); 
                    $daysLeft = $today->diffInDays($lastDate) + 1; 
                    $batch->days_left = $daysLeft;

                    return $batch;
                });
        if($batches){

            foreach ($batches as $batch) { 



                $push_data              = []; 

                $push_data['tokens']    =  User::whereNotNull('refresh_token')
                                                ->where('id',1)
                                                ->pluck('refresh_token')->toArray();
                if ($batch->days_left == 1) {
                    
                    $push_data['title'] = 'Hurry up! Today is the last day for enrollment – ' . $batch->course->course_name;
                }else{

                    $push_data['title']     =   'Reminder : Only ' .$batch->days_left.' Days Left for Enrollment – '.$batch->course->course_name;
                }

                $push_data['body']      =   'Enroll now in our next batch of '.$batch->course->course_name.' starting '.$batch->start_date.'.'.PHP_EOL.'Last date for enrollment: '.$batch->last_date.'. Don’t miss out';

                $push_data['route']         =   'NewBatch';
                $push_data['id']            =   $batch['id'];
                $push_data['category']      =   'NewBatch';

                $push_data['data1']         =   $batch->course->course_name;
                $push_data['data2']         =   $batch->start_date;
                $push_data['data3']         =   $batch->last_date;
                $push_data['data4']         =   null;
                $push_data['data5']         =   null;
                $push_data['image1']        =   null;

                $pusher = new NotificationPusher();
                $pusher->push($push_data);
                
                Log::info('Notifications for upcoming courses have been sent.Batch Id-'.$batch['id']);
            }

        }
    }
}
