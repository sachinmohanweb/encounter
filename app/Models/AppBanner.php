<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppBanner extends Model
{
    use HasFactory;

    protected $table = 'app_banners';

    protected $fillable = [
        'title',
        'link',
        'path',       
        'status'        
    ];

    public function getStatusAttribute($value)
    {
        if($value==1){
            return 'Pending';
        }else if($value==2){
            return 'Active';
        }
    }

}
