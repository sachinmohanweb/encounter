<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $appends = ['user_name'];

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'age',
        'location',
        'image',
        'device_type',
        'ip',
        'device_id',
        'refresh_token',
        'app_usage',
        'browser',
        'last_accessed',
        'email',
        'status',
        'country_code',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getUserNameAttribute()
    {
        $user = User::where('id',$this->id)->first();
        $user_name = $user['first_name'].' '.$user['last_name'];
        return $user_name;
    }
}
