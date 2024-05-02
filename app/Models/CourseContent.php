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

    public function getStatusAttribute($value)
    {
        return $value == 1 ? 'Active' : 'Suspended';
    }
}
