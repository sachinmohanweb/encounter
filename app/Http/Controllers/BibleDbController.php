<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;


use DB;
use Session;
use Exception;
use Datatables;

use App\Models\Book;
use App\Models\Bible;
use App\Models\Chapter;
use App\Models\Testament;
use App\Models\HolyStatement;

class BibleDbController extends Controller
{
    
    public function bible_list(Request $request): JsonResponse
    {
        $searchTerm = $request->input('search_tag');

        $bibles = Bible::where('bible_name', 'like',  $searchTerm . '%')
                        ->get(['bible_id', 'bible_name']);
        $results = [];

        foreach ($bibles as $bible) {
            $results[] = [
                'id' => $bible->bible_id,
                'text' => $bible->bible_name,
            ];
        }
        return response()->json(['results' => $results]);
    }
    
    public function testament_list(Request $request): JsonResponse
    {
        $searchTerm = $request->input('search_tag');

        $testaments = Testament::where('testament_name', 'like',  $searchTerm . '%');

        if($request['bible_id']){
            $testaments = $testaments->where('bible_id',$request['bible_id']);
        }
        $testaments = $testaments->get(['testament_id', 'testament_name']);

                        
        $results = [];

        foreach ($testaments as $testament) {
            $results[] = [
                'id' => $testament->testament_id,
                'text' => $testament->testament_name,
            ];
        }
        return response()->json(['results' => $results]);
    }

    public function book_list(Request $request): JsonResponse
        {
            $searchTerm = $request->input('search_tag');

            $books = Book::where('book_name', 'like',  $searchTerm . '%');

            if($request['testament_id']){
                    $books = $books->where('testament_id',$request['testament_id']);
            }
            $books = $books ->get(['book_id', 'book_name']);
                           
            $results = [];

            foreach ($books as $book) {
                $results[] = [
                    'id' => $book->book_id,
                    'text' => $book->book_name,
                ];
            }
            return response()->json(['results' => $results]);
        }

    public function chapter_list(Request $request): JsonResponse
    {
        $searchTerm = $request->input('search_tag');

        $chapters = Chapter::where('chapter_name', 'like',  $searchTerm . '%');
        
        if($request['book_id']){
            $chapters = $chapters->where('book_id',$request['book_id']);
        }

        $chapters = $chapters->get(['chapter_id', 'chapter_name']);

        $results = [];

        foreach ($chapters as $chapter) {
            $results[] = [
                'id' => $chapter->chapter_id,
                'text' => $chapter->chapter_name,
            ];
        }
        return response()->json(['results' => $results]);       
    }

    public function verse_list(Request $request): JsonResponse
    {
        $searchTerm = $request->input('search_tag');
       
        $verses = HolyStatement::where('statement_id', 'like',  $searchTerm . '%');

        if($request['chapter_id']){
            $verses = $verses->where('chapter_id',$request['chapter_id']);
        }

        $verses = $verses->get(['statement_id','statement_no']);

        $results = [];

        foreach ($verses as $verse) {
            $results[] = [
                'id' => $verse->statement_id,
                'text' => $verse->statement_no,
            ];
        }
        return response()->json(['results' => $results]);       
    }
}
