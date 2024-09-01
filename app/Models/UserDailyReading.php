<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDailyReading extends Model
{
    use HasFactory;

    protected $appends = [];

    protected $fillable = [
        'user_lms_id',
        'day',
        'date_of_reading',
        'status',
    ];

    public function getUserNameAttribute()
    {
        $user = User::where('id',$this->user_id)->first();
        return $user->first_name.' '.$user->last_name;
    }
}
