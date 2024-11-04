<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseContentLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_content_id',
        'type',
        'title',
        'description',
        'video_spotify_link',
        'status',
    ];
    
    public function getStatusAttribute($value)
    {
        return $value == 1 ? 'Active' : 'Suspended';
    }
}
