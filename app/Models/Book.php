<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Book extends Model
{
    use HasFactory,Searchable;

    protected $connection = 'mysql_bible';
    protected $table = '04_book';
    protected $primaryKey = 'book_id';
    protected $guard = [];

    public function toSearchableArray() {
        return [
            'book_id' => $this->book_id,
            'book_name' => $this->book_name,
        ];
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class,'book_id','book_id');
    }
}
