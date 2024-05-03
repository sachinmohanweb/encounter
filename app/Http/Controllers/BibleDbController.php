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
use App\Models\Chapter;
use App\Models\HolyStatement;

class BibleDbController extends Controller
{
    
    public function book_list(Request $request): JsonResponse
    {
        $searchTerm = $request->input('search_tag');

        $books = Book::where('book_name', 'like',  $searchTerm . '%')
                        ->get(['book_id', 'book_name']);
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

        $verses = $verses->get(['statement_id']);

        $results = [];

        foreach ($verses as $verse) {
            $results[] = [
                'id' => $verse->statement_id,
                'text' => $verse->statement_id,
            ];
        }
        return response()->json(['results' => $results]);       
    }
}
