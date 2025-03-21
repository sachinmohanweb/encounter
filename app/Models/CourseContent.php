<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Auth;

class CourseContent extends Model
{
    use HasFactory;
    protected $courseBatchId;

    protected $fillable = [
        'course_id',
        'day',
        'text_description',
        'audio_file',
        'website_link',
        'image',
        'documents',
        'status',
    ];
    
    protected $appends = ['course_name','bible_name','completed_status'];


    public function getStatusAttribute($value)
    {
        return $value == 1 ? 'Active' : 'Suspended';
    }

    public function getCourseNameAttribute()
    {
        $course = Course::where('id',$this->course_id)->first();
        return $course->course_name;
    }

    public function getBibleNameAttribute()
    {
        $course = Course::where('id',$this->course_id)->first();
        $bible = Bible::where('bible_id',$course->bible_id)->first();
        return $bible->bible_name;
    }
    public function CourseDayVerse(){

        return $this->hasMany(CourseDayVerse::class,'course_content_id', 'id');
    }
    public function CourseContentVideoLink(){

        return $this->hasMany(CourseContentLink::class,'course_content_id', 'id')->where('type', 1);
    }
    public function CourseContentSpotifyLink(){

        return $this->hasMany(CourseContentLink::class,'course_content_id', 'id')->where('type', 2);
    }

    public function setCourseBatchId($batchId)
    {
        $this->courseBatchId = $batchId;
    }

    public function getCompletedStatusAttribute()
    {
        $status = False;
        $user = Auth::user();
        if($user){

            $user_lms = UserLMS::where('user_id',$user->id)
                        ->where('course_id',$this->course_id)
                        ->where('batch_id', $this->courseBatchId)->where('status',1)->first();
            if($user_lms){
                $user_readings = UserDailyReading::where('user_lms_id',$user_lms->id)
                        ->where('day',$this->day)->where('status',1)->first();
                if($user_readings){
                    $status = True;
                }
            }
        }
        return $status;
    }
}
