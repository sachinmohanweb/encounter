<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Laravel\Scout\Searchable;

class UserCustomNote extends Model
{
    use HasFactory,Searchable;

    protected $connection = 'mysql';

    protected $appends = ['user_name','tag_name'];

    protected $fillable = [
        'user_id',
        'note_text',
        'tag_id',
        'status',
    ];

    public function toSearchableArray() {
        return [
            'id' => $this->id,
            'note_text' => $this->note_text,
        ];
    }

    public function getUserNameAttribute()
    {
        $user = User::where('id',$this->user_id)->first();
        return $user->first_name.' '.$user->last_name;
    }

    public function getTagNameAttribute()
    {
        $tag = Tag::where('id',$this->tag_id)->first();
        return $tag ? $tag->tag_name : null;
    }
}
