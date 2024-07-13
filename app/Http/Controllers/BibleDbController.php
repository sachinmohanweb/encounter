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

    public function BibleView() : View
    {
        return view('bible_view.BibleView',[]);
    }

    public function BibleVeiewDatatable(Request $request)
    {
        if(request()->ajax()) {

            $query = HolyStatement::with(['bible', 'testament', 'book', 'chapter'])
                    ->select('chapter_id', 'bible_id', 'testament_id', 'book_id', DB::raw('COUNT(*) as total_verse'));
            if($request['bible_id']){
                    $query->where('bible_id',$request['bible_id']);
            }
            if($request['testament_id']){
                    $query->where('testament_id',$request['testament_id']);
            }
            if($request['book_id']){
                    $query->where('book_id',$request['book_id']);
            }
            if($request['chapter_id']){
                    $query->where('chapter_id',$request['chapter_id']);
            }
            $query = $query->groupBy('chapter_id', 'bible_id', 'testament_id', 'book_id');
            return datatables()
                ->of($query)
                ->addColumn('bible', function ($holyStatement) {
                    return $holyStatement->bible->bible_name;
                })
                ->addColumn('testament', function ($holyStatement) {
                    return $holyStatement->testament->testament_name;
                })
                ->addColumn('book', function ($holyStatement) {
                    return $holyStatement->book->book_name;
                })
                ->addColumn('chapter', function ($holyStatement) {
                    return $holyStatement->chapter->chapter_name;
                })
                ->addColumn('total_verse', function ($holyStatement) {
                    return $holyStatement->total_verse;
                })
                ->addColumn('action', 'bible_view.datatable-action')
                ->rawColumns(['bible', 'testament', 'book', 'chapter', 'total_verse', 'action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('bible_view.BibleView');
    }

    public function BibleViewRead($chapter_id) : View
    {
        
        $verses = HolyStatement::select('*')->where('chapter_id',$chapter_id)->get();
        $chapter_details = HolyStatement::select('*')->where('chapter_id',$chapter_id)->first();

        return view('bible_view.ReadBibleVerse',compact('verses','chapter_details'));

    }
}
