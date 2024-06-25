<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyBibleVerse extends Model
{
    use HasFactory;

    protected $appends = ['bible_name','testament_name','book_name','chapter_name','verse_no','theme_name'];

    protected $fillable = [
        'bible_id',
        'testament_id',
        'book_id',
        'chapter_id',
        'verse_id',
        'date',
        'theme_id',
        'status',
    ];

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
        return $chapter->chapter_name;
    }

    public function getVerseNoAttribute()
    {
        $verse = HolyStatement::where('statement_id',$this->verse_id)->first();
        return $verse->statement_no;
    }

    public function getThemeNameAttribute()
    {
        $theme = BibleVerseTheme::where('id',$this->theme_id)->first();
        return $theme->name;
    }


}
