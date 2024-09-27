<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookImage extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql_bible';

    protected $fillable = [
        'bible_id',
        'testament_id',
        'book_id',
        'image',
        'status',
    ];
    
    public function getStatusAttribute($value)
    {
        return $value == 1 ? 'Active' : 'Suspended';
    }

    public function bible()
    {
        return $this->belongsTo(Bible::class,'bible_id','bible_id');
    }
    public function testament()
    {
        return $this->belongsTo(Testament::class,'testament_id','testament_id');
    }
    public function book()
    {
        return $this->belongsTo(Book::class,'book_id','book_id');
    }
}
