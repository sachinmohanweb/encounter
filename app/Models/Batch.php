<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Batch extends Model
{
    use HasFactory,Searchable;

    protected $fillable = [
        'course_id',
        'batch_name',
        'start_date',
        'end_date',
        'last_date',
        'date_visibility',
        'status'
    ];

    public function toSearchableArray() {
        return [
            'id' => $this->id,
            'batch_name' => $this->batch_name,
        ];
    }

    public function getStatusAttribute($value)
    {
        return $value == 1 ? 'Active' : 'Suspended';
    }

    public function course()
    {
        return $this->belongsTo(Course::class,'course_id','id');
    }
}
