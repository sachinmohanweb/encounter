<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tag_name',
        'status',
    ];

    public function getStatusAttribute($value)
    {
        return $value == 1 ? 'Active' : 'Suspended';
    }

    public function getBibleMarkings($userId, $type = 2)
    {

        return \DB::table('user_bible_markings')
            ->where('user_id', $userId)
            ->where('type', $type)
            ->whereRaw("FIND_IN_SET(?, data)", [$this->id])
            ->pluck('statement_id')
            ->toArray();
    }

    public function getCustomNotes($userId)
    {
        return \DB::table('user_custom_notes')
            ->select('id','note_text')
            ->where('user_id', $userId)
            ->where('tag_id',$this->id)
            ->get()
            ->toArray();
    }

}
