<?php

namespace App\Http\Repositories;

use App\Models\User;
use App\Models\HolyStatement;

class UserRepository {

    function __construct() {
        
    }

    function checkUser($data){

        $user=User::where('email',$data['email'])->where('status',1)->first();
        
        if($user)
        {
            return $user;

        }else{

            return '';
        }   
    }

    function formatBibleVerse($item)
    {
        $sentences = preg_split('/(?<=[.?!])\s+/', $item->statement_text, -1, PREG_SPLIT_NO_EMPTY);
        $snippet = implode(' ', array_slice($sentences, 0, 2));

        return [
            'type' => 'Bible Verse',
            'result' => $snippet,
            'id' => $item->statement_id,
            'book_id' => $item->book_id,
            'chapter_id' => $item->chapter_id,
            'chapter_no' => $item->chapter->chapter_no,
            'reference' => sprintf('%s %d:%d', $item->book->book_name, $item->chapter->chapter_no, $item->statement_no),
        ];
    }

    function searchAndHighlight($searchTerm)
    {
        return HolyStatement::search($searchTerm)
            ->orderBy('statement_id')
            ->get()
            ->filter(fn($item) => stripos($item->statement_text, $searchTerm) !== false)
            ->map(function ($item) use ($searchTerm) {
                $contextWords = 8;
                preg_match('/(?:\S+\s+){0,' . $contextWords . '}\S*' . preg_quote($searchTerm, '/') . '\S*(?:\s+\S+){0,' . $contextWords . '}/i', $item->statement_text, $matches);
                $highlightedText = isset($matches[0]) ? preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $matches[0]) . '.....' : $item->statement_text;

                return [
                    'type' => 'Bible Verse',
                    'result' => $highlightedText,
                    'id' => $item->statement_id,
                    'book_id' => $item->book_id,
                    'chapter_id' => $item->chapter_id,
                    'chapter_no' => $item->chapter->chapter_no,
                    'reference' => sprintf('%s %d:%d', $item->book->book_name, $item->chapter->chapter_no, $item->statement_no),
                ];
            });
    }
}