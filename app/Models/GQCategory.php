<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GQCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];
    
    public function getStatusAttribute($value)
    {
        return $value == 1 ? 'Active' : 'Suspended';
    }

}
