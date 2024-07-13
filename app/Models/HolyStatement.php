<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolyStatement extends Model
{
    use HasFactory;

    protected $connection = 'mysql_bible';
    protected $table = '06_holy_statement';
    protected $guard = [];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class,'chapter_id','chapter_id');
    }

    public function Bible()
    {
        return $this->belongsTo(Bible::class,'bible_id','bible_id');
    }
    public function Testament()
    {
        return $this->belongsTo(Testament::class,'testament_id','testament_id');
    }
    public function Book()
    {
        return $this->belongsTo(Book::class,'book_id','book_id');
    }
}
