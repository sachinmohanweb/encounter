<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'redirection',
        'type',
        'data',
        'status',
    ];

    protected $appends = ['type_name'];

    public function getStatusAttribute($value)
    {
        return $value == 1 ? 'Active' : 'Suspended';
    }
  
    public function TypeData()
    {
        return $this->belongsTo(NotificationType::class,'type','id');
    }   
    
    public function getTypeNameAttribute()
    {
        $type = NotificationType::where('id',$this->type)->first();
        return $type->type_name;
    }
}
