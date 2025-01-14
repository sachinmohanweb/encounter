<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BibleVerseImage extends Model
{
    use HasFactory;

    protected $table = 'bible_verse_images';

    protected $fillable = [
        'title',
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
