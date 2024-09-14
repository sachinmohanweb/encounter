<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GQSubCategory extends Model
{
    use HasFactory;

    protected $table = 'g_q_subcategories';
    
    protected $fillable = [
        'cat_id',
        'name',
        'status',
    ];
    
    public function getStatusAttribute($value)
    {
        return $value == 1 ? 'Active' : 'Suspended';
    }

    public function category()
    {
        return $this->belongsTo(GQCategory::class,'cat_id','id');
    }
}
