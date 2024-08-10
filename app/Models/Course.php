<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_name',
        'bible_id',
        'course_creator',
        'creator_designation',
        'creator_image',
        'no_of_days',
        'description',
        'thumbnail',
        'intro_commentary',
        'intro_video',
        'intro_audio',
        'status',
    ];

    protected $appends = ['bible_name'];


    public function getStatusAttribute($value)
    {
        return $value == 1 ? 'Active' : 'Suspended';
    }

    public function getBibleNameAttribute()
    {
        $bible = Bible::where('bible_id',$this->bible_id)->first();
        return $bible->bible_name;
    }

    public function contentExistsForDay($day)
    {
        return $this->CourseContents()->where('day', $day)->exists();
    }

    public function CourseContents(){

        return $this->hasMany(CourseContent::class,'course_id', 'id');
    }

    public function getContentForDay($day)
    {
        return $this->CourseContents()->where('day', $day)->first();
    }
}
