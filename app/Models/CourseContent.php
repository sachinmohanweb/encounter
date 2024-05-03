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
        'book',
        'chapter',
        'verse_from',
        'verse_to',
        'text_description',
        'video_link',
        'audio_file',
        'spotify_link',
        'website_link',
        'image',
        'documents',
        'status',
    ];
    protected $appends = ['course_name','book_name','chapter_name'];


    public function getStatusAttribute($value)
    {
        return $value == 1 ? 'Active' : 'Suspended';
    }

    public function getCourseNameAttribute()
    {
        $course = Course::where('id',$this->course_id)->first();
        return $course->course_name;
    }
    public function getBookNameAttribute()
    {
        $book = Book::where('book_id',$this->book)->first();
        return $book->book_name;
    }
    public function getChapterNameAttribute()
    {
        $chapter = Chapter::where('chapter_id',$this->chapter)->first();
        return $chapter->chapter_name;
    }
}
