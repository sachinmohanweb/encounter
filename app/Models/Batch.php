<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'batch_name',
        'start_date',
        'end_date',
        'last_date',
        'status'
    ];

    public function getStatusAttribute($value)
    {
        return $value == 1 ? 'Active' : 'Suspended';
    }

    public function course()
    {
        return $this->belongsTo(Course::class,'course_id','id');
    }
}
