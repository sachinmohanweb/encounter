<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseContent extends Model
{
    use HasFactory;

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
    
    protected $appends = ['course_name','bible_name'];


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

}
