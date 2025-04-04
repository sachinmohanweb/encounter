<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $connection = 'mysql_bible';
    protected $table = '05_chapter';
    protected $guard = [];

    public function statements()
    {
        return $this->hasMany(HolyStatement::class,'chapter_id','chapter_id');
    }
}
