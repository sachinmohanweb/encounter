<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Auth;

class HolyStatement extends Model
{
    use HasFactory;

    protected $connection = 'mysql_bible';
    protected $table = '06_holy_statement';
    protected $primaryKey = 'statement_id';
    public $timestamps = false;
    protected $fillable = ['statement_text','statement_heading'];

    protected $appends = ['note_marking','bookmark_marking','color_marking'];

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

    public function getNoteMarkingAttribute()
    {
        $color_data = UserBibleMarking::where('statement_id',$this->statement_id)
                        ->where('user_id',Auth::id())
                        ->where('type',1)
                        ->first();
        if($color_data){

            return $color_data['data'];
        }else{
            return Null;
        }
    }

    public function getBookmarkMarkingAttribute()
    {
        $color_data = UserBibleMarking::where('statement_id',$this->statement_id)
                        ->where('user_id',Auth::id())
                        ->where('type',2)
                        ->first();
        if($color_data){

            $tagIds = explode(',', $color_data['data']);

            $tags = Tag::whereIn('id', $tagIds)
                    ->select('id', 'tag_name')
                    ->get();
            return $tags;
        }else{
            return Null;
        }
    }

    public function getColorMarkingAttribute()
    {
        $color_data = UserBibleMarking::where('statement_id',$this->statement_id)
                        ->where('user_id',Auth::id())
                        ->where('type',3)
                        ->first();
        if($color_data){

            return $color_data['data'];
        }else{
            return Null;
        }
    }
}
