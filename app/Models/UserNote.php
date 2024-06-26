<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNote extends Model
{
    use HasFactory;

    protected $appends = ['user_name','bible_name','testament_name','book_name','chapter_name','verse_no'];

    protected $fillable = [
        'user_id',
        'bible_id',
        'testament_id',
        'book_id',
        'chapter_id',
        'verse_id',
        'note',
        'category',
        'sub_category',
        'status',
    ];

    public function getUserNameAttribute()
    {
        $user = User::where('id',$this->user_id)->first();
        return $user->first_name.' '.$user->last_name;
    }

    public function getBibleNameAttribute()
    {
        $bible = Bible::where('bible_id',$this->bible_id)->first();
        return $bible->bible_name;
    }

    public function getTestamentNameAttribute()
    {
        $testament = Testament::where('testament_id',$this->testament_id)->first();
        return $testament->testament_name;
    }

    public function getBookNameAttribute()
    {
        $book = Book::where('book_id',$this->book_id)->first();
        return $book->book_name;
    }

    public function getChapterNameAttribute()
    {
        $chapter = Chapter::where('chapter_id',$this->chapter_id)->first();
        if($chapter){
            return $chapter->chapter_name;
        }else{
            return '';
        }
    }

    public function getVerseNoAttribute()
    {
        $verse = HolyStatement::where('statement_id',$this->verse_id)->first();
        if($verse){
            return $verse->statement_no;
        }else{
            return '';
        }
    }
}
