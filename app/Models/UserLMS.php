<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLMS extends Model
{
    use HasFactory;

    protected $appends = ['user_name','course_name','batch_name','completed_status_name'];

    protected $fillable = [
        'user_id',
        'course_id',
        'batch_id',
        'start_date',
        'end_date',
        'progress',
        'completed_status',
        'status',
    ];

    public function getUserNameAttribute()
    {
        $user = User::where('id',$this->user_id)->first();
        return $user->first_name.' '.$user->last_name;
    }

    public function getCourseNameAttribute()
    {
        $course = Course::where('id',$this->course_id)->first();
        return $course->course_name;
    }

    public function getBatchNameAttribute()
    {
        $batch = Batch::where('id',$this->batch_id)->first();
        return $batch->batch_name;
    }

    public function getCompletedStatusNameAttribute()
    {
        if($this->completed_status==1){

            return "Not Started";

        }else if($this->completed_status==2){

            return "Ongoing";
        }else{

            return "Completed";
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

}
