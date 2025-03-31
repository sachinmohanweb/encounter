<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class BibleChange extends Model
{
    use HasFactory,Searchable;

    protected $fillable = [
        'bible_id',
        'statement_id',
        'sync_time'
    ];
    
}
