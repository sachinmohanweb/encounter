<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $connection = 'mysql_bible';
    protected $table = '04_book';
    protected $guard = [];

    public function chapters()
    {
        return $this->hasMany(Chapter::class,'book_id','book_id');
    }
}
