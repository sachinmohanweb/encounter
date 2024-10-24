<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testament extends Model
{
    use HasFactory;

    protected $connection = 'mysql_bible';
    protected $table = '03_testament';
    protected $guard = [];

    public function books()
    {
        return $this->hasMany(Book::class,'testament_id','testament_id');
    }
}
