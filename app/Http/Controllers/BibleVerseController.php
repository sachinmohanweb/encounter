<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

use DB;
use Excel;
use Cache;
use Session;
use Exception;

use App\Models\Book;
use App\Models\Bible;
use App\Models\Chapter;
use App\Models\Testament;
use App\Models\HolyStatement;
use App\Models\BibleVerseTheme;
use App\Models\DailyBibleVerse;

use App\Imports\DailyBibleVerseImport;

class BibleVerseController extends Controller
{
    
    public function DailyBibleVerse() : View
    {
        return view('bible_verse.DailyBibleVerse',[]);
    }

    public function BibleVerseDatatable()
    {
        if(request()->ajax()) {
            return datatables()
            ->of(DailyBibleVerse::select('*'))
            ->addColumn('bible', function ($bibleverse) {
                return $bibleverse->bible_name;
            })
            ->addColumn('testament', function ($bibleverse) {
                return $bibleverse->testament_name;
            })
            ->addColumn('book', function ($bibleverse) {
                return $bibleverse->book_name;
            })
            ->addColumn('chapter', function ($bibleverse) {
                return $bibleverse->chapter_name;
            })
            ->addColumn('verse', function ($bibleverse) {
                return $bibleverse->verse_no;
            })
            ->addColumn('theme', function ($bibleverse) {
                return $bibleverse->theme_name;
            })
            ->addColumn('action', 'bible_verse.datatable-action')
            ->addColumn('status', 'bible_verse.status_datatable-action')
            ->rawColumns(['bible','book','chapter','verse','theme','action','status'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('bible_verse.DailyBibleVerse');
    }

    public function AddDailyBibleVerse() : View
    {
        $default_bible_id=env('DEFAULT_BIBLE');
        $default_bible = Bible::where('bible_id',$default_bible_id)->first();

        return view('bible_verse.AddDailyBibleVerse',compact('default_bible_id','default_bible'));
    }

    public function StoreDailyBibleVerse(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $a =  $request->validate([
                'bible_id' => 'required',
                'testament_id' => 'required',
                'book_id' => 'required',
                'chapter_id' => 'required',
                'verse_id' => 'required',   
            ]);
            $inputData = $request->all();
            if (isset($inputData['date'])) {
                $inputData['date'] = \Carbon\Carbon::createFromFormat('m/d/Y', $inputData['date'])->format('Y-m-d');
            }
            
            $verse = DailyBibleVerse::create($inputData);
            DB::commit();
             
            return redirect()->route('admin.daily.bible.verse')
                            ->with('success',"Success! New Daily Bible Verse  has been successfully added.");
        }catch (Exception $e) {
            DB::rollBack();
            $message = $e->getMessage();
            return back()->withInput()->withErrors(['message' =>  $e->getMessage()]);;
        }
    }

    public function EditDailyBibleVerse($id) : View
    {
        $default_bible_id=env('DEFAULT_BIBLE');
        $default_bible = Bible::where('bible_id',$default_bible_id)->first();

        $verse =DailyBibleVerse::find($id);

        $testaments = Testament::all();
        $books = Book::all();
        $chapters = Chapter::all();
        $verses = HolyStatement::select('statement_id','statement_no')->get();
        $themes = BibleVerseTheme::all();

        return view('bible_verse.EditDailyBibleVerse',compact('default_bible_id','default_bible','verse',
            'testaments','books','chapters','verses','themes'));

    }

    public function UpdateDailyBibleVerse(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {

            $verse = DailyBibleVerse::find($request->id);

            $a =  $request->validate([
                'bible_id' => 'required',
                'testament_id' => 'required',
                'book_id' => 'required',
                'chapter_id' => 'required',
                'verse_id' => 'required',   
            ]);
            $inputData = $request->all();

            if (isset($inputData['date'])) {
                $inputData['date'] = \Carbon\Carbon::createFromFormat('m/d/Y', $inputData['date'])->format('Y-m-d');
            }
            
            $verse->update($inputData);
            DB::commit();
             
            return redirect()->route('admin.daily.bible.verse')
                            ->with('success',"Success! New Daily Bible Verse  has been successfully updated.");
        }catch (Exception $e) {
            DB::rollBack();
            $message = $e->getMessage();
            return back()->withInput()->withErrors(['message' =>  $e->getMessage()]);;
        }
    }

    public function DeleteDailyBibleVerse(Request $request) : JsonResponse
    {
        DB::beginTransaction();
        try{
            $verse =DailyBibleVerse::where('id',$request->id)->first();
            if($verse){
                $verse->delete();
                DB::commit();
                $return['status'] = "success";
            }else{
                $return['status'] = 'failed';
            }

         }catch (Exception $e) {

            DB::rollBack();
            $return['status'] = $e->getMessage();
        }
        return response()->json($return);
    }

    public function BibleVerseTheme(Request $request): JsonResponse
    {
        $searchTerm = $request->input('search_tag');

        $themes = BibleVerseTheme::where('name', 'like',  $searchTerm . '%')
                            ->get(['id', 'name']);

                        
        $results = [];

        foreach ($themes as $theme) {
            $results[] = [
                'id' => $theme->id,
                'text' => $theme->name,
            ];
        }
        return response()->json(['results' => $results]);
    }

    public function admin_bible_verse_status_change(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $verse = DailyBibleVerse::find($request['id']);
            if($verse->status==1){
                $verse->status=2;
            }else{
                $verse->status=1;
            }
            $verse->save();;
            DB::commit();

            return response()->json(['success' => true ,'msg' => 'Bible Verse status updated','status' =>$verse['status']]);

        }catch (Exception $e) {

            DB::rollBack();
            $message = $e->getMessage();

            return response()->json(['success' => false,'msg' => $e->getMessage()]);
        }
    }

    public function BibleVerseThemeList() : View
    {
        return view('bible_verse.BibleVerseTheme',[]);
    }

    public function BibleVerseThemeDatatable()
    {
        if(request()->ajax()) {
            return datatables()
            ->of(BibleVerseTheme::select('*'))
            ->addColumn('id', function ($bibleverse) {
                return $bibleverse->id;
            })
            ->addColumn('name', function ($bibleverse) {
                return $bibleverse->name;
            })
        
            ->addColumn('action', 'bible_verse.theme_datatable-action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('bible_verse.DailyBibleVerse');
    }

    public function StoreBibleVerseTheme(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $a =  $request->validate([
                'name' => 'required',   
            ]);
            $inputData = $request->all();
            
            $theme = BibleVerseTheme::create($inputData);
            DB::commit();

            $return['messsage']  =  'success';
            $return['theme']     =  $theme; 
            return response()->json($return);
        }catch (Exception $e) {
            DB::rollBack();
            $return['result']=$e->getMessage();
            return response()->json(['success' => false,'msg' => $e->getMessage()]);
        }
    }

    public function UpdateBibleVerseTheme(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $data =  $request->validate([
                'name' => 'required',   
            ]);
            
            $theme = BibleVerseTheme::findOrFail($request->id);
            $theme->update($data);
            DB::commit();

            $return['messsage']  =  'success';
            $return['theme']     =  $theme; 
            
            return redirect()->route('admin.bible.verse.theme')
                            ->with('success',"Success!  Bible Verse  theme has been successfully updated.");
        }catch (Exception $e) {
            DB::rollBack();
            $message = $e->getMessage();
            return back()->withInput()->withErrors(['message' =>  $e->getMessage()]);;
        }
    }

    public function DeleteBibleVerseTheme(Request $request) : JsonResponse
    {
        DB::beginTransaction();
        try{
            $theme =BibleVerseTheme::where('id',$request->id)->first();
            if($theme){
                $daily_verse =DailyBibleVerse::where('theme_id',$request->id)->first();

                if(($daily_verse )){
                    $return['status'] = 'Forbidden';
                }else{
                    $theme->delete();
                    DB::commit();
                    $return['status'] = "success";  
                }
            }else{
                $return['status'] = 'failed';
            }

         }catch (Exception $e) {

            DB::rollBack();
            $return['status'] = $e->getMessage();
        }
        return response()->json($return);
    }

    public function ImportBibleVerse() : View
    {
        return view('bible_verse.ImportBibleVerse',[]);
    }

    public function import_progress_bible_verse(Request $request)
    {
        $progress = Cache::get('import_progress_bible', 0);

        return response()->json(['progress' => $progress]);
    }

    public function StoreImportBibleVerse(Request $request) : JsonResponse
    {
        $fileData=$request->file('excel_file');

        $bible_verse_import = new DailyBibleVerseImport();
        Excel::import($bible_verse_import, $fileData);
        $output = $bible_verse_import->getImportResult();
        return response()->json([$output]);
    }
}
