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
        return $this->belongsTo(Chapter::class);
    }
}
