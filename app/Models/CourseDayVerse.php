<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseDayVerse extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_content_id',
        'testament',
        'book',
        'chapter',
        'verse_from',
        'verse_to',
        'status',
    ];
    protected $appends = ['testament_name','book_name','chapter_name','chapter_no','verse_from_name','verse_to_name'];


    public function getStatusAttribute($value)
    {
        return $value == 1 ? 'Active' : 'Suspended';
    }

    public function getTestamentNameAttribute()
    {
        $testament = Testament::where('testament_id',$this->testament)->first();
        return $testament->testament_name;
    }

    public function getBookNameAttribute()
    {
        $book = Book::where('book_id',$this->book)->first();
        return $book->book_name;
    }

    public function getChapterNameAttribute()
    {
        $chapter = Chapter::where('chapter_id',$this->chapter)->first();
        return $chapter->chapter_name;
    }

    public function getChapterNoAttribute()
    {
        $chapter = Chapter::where('chapter_id',$this->chapter)->first();
        return $chapter->chapter_no;
    }

    public function getVerseFromNameAttribute()
    {
        $statement = HolyStatement::where('statement_id',$this->verse_from)->first();
        return $statement->statement_no;
    }

    public function getVerseToNameAttribute()
    {
        $statement = HolyStatement::where('statement_id',$this->verse_to)->first();
        return $statement->statement_no;
    }

    public function getFormattedCourseDaySections(): string
    {
        $bookName = $this->book_name;
        $chapterName = $this->chapter_no;
        $verseFromName = $this->verse_from_name;
        $verseToName = $this->verse_to_name;

        return $bookName . ' ' . $chapterName . ':' . $verseFromName . '-' . $verseToName;
    }

    public function isMarkedAsRead(int $userId, int $batchId, string $day): bool
    {
        $userLMS = UserLMS::where('user_id', $userId)
                    ->where('batch_id', $batchId)
                    ->where('status', 1)
                    ->first();
        if (!$userLMS) {
            return false;
        }
        $readingExists = UserDailyReading::where('user_lms_id', $userLMS->id)
                            ->where('day', $day)
                            ->exists();
        return $readingExists;
    }
}
