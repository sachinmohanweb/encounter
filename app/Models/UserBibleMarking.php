<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBibleMarking extends Model
{
    use HasFactory;

    protected $appends = ['user_name','type_name','bible_name','testament_name','book_name','chapter_name','verse_no'];

    protected $fillable = [
        'user_id',
        'type',
        'statement_id',
        'data',
        'status',
    ];

    public function getUserNameAttribute()
    {
        $user = User::where('id',$this->user_id)->first();
        return $user->first_name.' '.$user->last_name;
    }

    public function getTypeNameAttribute()
    {
        if($this->type==1){

            $type_name ='Note';

        }elseif($this->type==2){

            $type_name ='Bookmark'; 
        }else{
            
            $type_name ='Color'; 

        }

        return $type_name;
    }

    public function getVerseNoAttribute()
    {
        $verse = HolyStatement::where('statement_id',$this->statement_id)->first();
        if($verse){
            return $verse->statement_no;
        }else{
            return '';
        }
    }
    public function getBibleNameAttribute()
    {   
        $verse = HolyStatement::where('statement_id',$this->statement_id)->first();
        if($verse){
            $bible = Bible::where('bible_id',$verse->bible_id)->first();
            return $bible->bible_name;
        }else{
            return '';
        }
    }

    public function getTestamentNameAttribute()
    {
        $verse = HolyStatement::where('statement_id',$this->statement_id)->first();
        if($verse){
            $testament = Testament::where('testament_id',$verse->testament_id)->first();
            return $testament->testament_name;
        }else{
            return '';
        }

    }

    public function getBookNameAttribute()
    {
        $verse = HolyStatement::where('statement_id',$this->statement_id)->first();
        if($verse){
            $book = Book::where('book_id',$verse->book_id)->first();
            return $book->book_name;
        }else{
            return '';
        }
    }

    public function getChapterNameAttribute()
    {
        $verse = HolyStatement::where('statement_id',$this->statement_id)->first();
        if($verse){
            $chapter = Chapter::where('chapter_id',$verse->chapter_id)->first();
            return $chapter->chapter_name;
        }else{
            return '';
        }
    }

}
