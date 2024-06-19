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
        'no_of_days',
        'description',
        'thumbnail',
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

        return $this->hasMany(CourseContent::class);
    }

    public function getContentForDay($day)
    {
        return $this->CourseContents()->where('day', $day)->first();
    }
}
