<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQNA extends Model
{
    use HasFactory;

    protected $table = 'user_qna';

    protected $appends = ['user_name'];


    protected $fillable = [
        'user_id',
        'question',
        'answer',
        'status'
    ];

    public function getStatusAttribute($value)
    {
        if($value==1){
            return 'Pending';
        }else if($value==2){
            return 'Answered';
        }else{
            return 'Suspended';
        }
    }

    public function getUserNameAttribute()
    {
        $user = User::where('id',$this->user_id)->first();
        return $user->first_name;
    }


}
