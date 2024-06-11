<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bible extends Model
{
    use HasFactory;

    protected $connection = 'mysql_bible';
    protected $table = '02_bible';
    protected $guard = [];

}
