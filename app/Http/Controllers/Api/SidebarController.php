<?php

namespace App\Http\Controllers\Api;

use DB;
use Mail;
use Auth;
use Cache;
use Artisan;
use Carbon\Carbon;

use App\Models\Tag;
use App\Models\UserQNA;
use App\Models\GQCategory;
use App\Models\GotQuestion;
use App\Models\GQSubCategory;
use App\Models\UserCustomNote;
use App\Models\UserBibleMarking;

use App\Models\Bible;
use App\Models\Testament;
use App\Models\Book;
use App\Models\Chapter;
use App\Models\HolyStatement;

use App\Helpers\Outputer;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;

use App\Http\Controllers\Controller;
class SidebarController extends Controller
{
    public function __construct(Outputer $outputer){

        $this->outputer = $outputer;
    }
    
    public function GotQuestions(Request $request){

        try {

            $pg_no='';
            $per_pg=100;

            $got_questions = GotQuestion::select('*')->where('status',1);

            if($request['gq_category']){
                $got_questions->where('category_id',$request['gq_category']);
            }
            if($request['gq_subcategory']){
                $got_questions->where('sub_category_id',$request['gq_subcategory']);
            }
            if($request['search_word']){
                $got_questions->where('question','like','%'.$request['search_word'].'%');
            }
            if($request['page_no']){
                $pg_no=$page=$request['page_no'];
            }
            if($request['per_page']){
               $per_pg=$page=$request['per_page'];
            }
            $got_questions=$got_questions->paginate($perPage=$per_pg,[],'',$page = $pg_no);

            if(empty($got_questions)) {
                
                $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" =>"Empty gospel question "
                    ]
                ];
            return $result;
            }

            $metadata = array(
                "total" => $got_questions->total(),
                "per_page" => $got_questions->perPage(),
                "current_page" => $got_questions->currentPage(),
                "last_page" => $got_questions->lastPage(),
                "next_page_url" => $got_questions->nextPageUrl(),
                "prev_page_url" => $got_questions->previousPageUrl(),
                "from" => $got_questions->firstItem(),
                "to" => $got_questions->lastItem()
            );

            return $this->outputer->code(200)->metadata($metadata)
                        ->success($got_questions->getCollection())->json();

        }catch (\Exception $e) {

            $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => $e->getMessage()
                    ]
                ];
            return $result;
        }
    }

    public function GotQuestionCategories(Request $request){

        try {

            $pg_no='';
            $per_pg=100;

            $got_question_cats = GQCategory::select('*')->where('status',1);

            if($request['search_word']){
                $got_question_cats->where('name','like','%'.$request['search_word'].'%');
            }
            if($request['page_no']){
                $pg_no=$page=$request['page_no'];
            }
            if($request['per_page']){
               $per_pg=$page=$request['per_page'];
            }
            $got_question_cats=$got_question_cats->paginate($perPage=$per_pg,[],'',$page = $pg_no);

            if(empty($got_question_cats)) {
                
                $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => "Empty category list "
                    ]
                ];
                return $result;

            }

            $metadata = array(
                "total" => $got_question_cats->total(),
                "per_page" => $got_question_cats->perPage(),
                "current_page" => $got_question_cats->currentPage(),
                "last_page" => $got_question_cats->lastPage(),
                "next_page_url" => $got_question_cats->nextPageUrl(),
                "prev_page_url" => $got_question_cats->previousPageUrl(),
                "from" => $got_question_cats->firstItem(),
                "to" => $got_question_cats->lastItem()
            );

            return $this->outputer->code(200)->metadata($metadata)
                        ->success($got_question_cats->getCollection())->json();

        }catch (\Exception $e) {

           $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => $e->getMessage()
                    ]
                ];
            return $result;
        }
    }

     public function GotQuestionSubCategories(Request $request){

        try {

            $pg_no='';
            $per_pg=100;

            $got_question_subcats = GQSubCategory::select('*')->where('status',1);

            if($request['gq_category']){
                $got_question_subcats->where('cat_id',$request['gq_category']);
            }

            if($request['search_word']){
                $got_question_subcats->where('name','like','%'.$request['search_word'].'%');
            }
            if($request['page_no']){
                $pg_no=$page=$request['page_no'];
            }
            if($request['per_page']){
               $per_pg=$page=$request['per_page'];
            }
            $got_question_subcats=$got_question_subcats->paginate($perPage=$per_pg,[],'',$page = $pg_no);

            if(empty($got_question_subcats)) {
                
                $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => "Empty sub category list "
                    ]
                ];
                return $result;
            }

            $metadata = array(
                "total" => $got_question_subcats->total(),
                "per_page" => $got_question_subcats->perPage(),
                "current_page" => $got_question_subcats->currentPage(),
                "last_page" => $got_question_subcats->lastPage(),
                "next_page_url" => $got_question_subcats->nextPageUrl(),
                "prev_page_url" => $got_question_subcats->previousPageUrl(),
                "from" => $got_question_subcats->firstItem(),
                "to" => $got_question_subcats->lastItem()
            );

            return $this->outputer->code(200)->metadata($metadata)
                        ->success($got_question_subcats->getCollection())->json();

        }catch (\Exception $e) {

           $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => $e->getMessage()
                    ]
                ];
            return $result;
        }
    }

    public function AskedQuestions(Request $request){

        try {

            $pg_no='';
            $per_pg=100;

            $user_id = Auth::user()->id;

            $user_asked_qna = UserQNA::select('*',DB::raw('DATE_FORMAT(created_at, "%b %d,%Y") as date_of_question'))
                    ->where('user_id',$user_id)
                    //->where('status',1)
                    ;

            if($request['search_word']){
                $user_asked_qna->where('question','like','%'.$request['search_word'].'%');
            }
            if($request['page_no']){
                $pg_no=$page=$request['page_no'];
            }
            if($request['per_page']){
               $per_pg=$page=$request['per_page'];
            }
            $user_asked_qna=$user_asked_qna->paginate($perPage=$per_pg,[],'',$page = $pg_no);

            if(empty($user_asked_qna)) {
                
                $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => "Empty blood group list "
                    ]
                ];
                return $result;
            }

            $metadata = array(
                "total" => $user_asked_qna->total(),
                "per_page" => $user_asked_qna->perPage(),
                "current_page" => $user_asked_qna->currentPage(),
                "last_page" => $user_asked_qna->lastPage(),
                "next_page_url" => $user_asked_qna->nextPageUrl(),
                "prev_page_url" => $user_asked_qna->previousPageUrl(),
                "from" => $user_asked_qna->firstItem(),
                "to" => $user_asked_qna->lastItem()
            );

            return $this->outputer->code(200)->metadata($metadata)
                        ->success($user_asked_qna->getCollection())->json();

        }catch (\Exception $e) {

            $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => $e->getMessage()
                    ]
                ];
            return $result;
        }
    }

    public function AskAQuestion(Request $request){
        
        DB::beginTransaction();

        try {

            $a =  $request->validate([
                    'question'      => 'required',
                ]);

            $user_id = Auth::user()->id;

            $inputData['user_id'] = $user_id;
            $inputData['question'] = $request['question'];

            $question = UserQNA::create($inputData);
            DB::commit();

            $return['messsage']  =  'Success.Your question submitted';
            return $this->outputer->code(200)->success($return)->json();


        }catch (\Exception $e) {

            DB::rollBack();
            $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => $e->getMessage()
                    ]
                ];
            return $result;
        }
    }

    public function CustomNotes(Request $request){

        try {

            $user_id = Auth::user()->id;

            $user_notes = UserCustomNote::select('*')
                        ->where('user_id',$user_id)
                        ->where('status',1)
                        ->get();

            if(empty($user_notes)) {

                $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => "Empty user notes "
                    ]
                ];
                return $result;
            }

            return $this->outputer->code(200)
                        ->success($user_notes)->json();

        }catch (\Exception $e) {

            $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => $e->getMessage()
                    ]
                ];
            return $result;
        }
    }

    public function AddCustomNote(Request $request){
        
        DB::beginTransaction();

        try {

            $user_id = Auth::user()->id;

            $a =  $request->validate([
                    'note_text'      => 'required',
                    'tag_id'  => 'required',
                ]);

            $inputData['user_id'] = $user_id;
            $inputData['note_text'] =$request['note_text'];
            $inputData['tag_id'] = $request['tag_id'];

            $note = UserCustomNote::create($inputData);
            DB::commit();

            $return['messsage']  =  'Success.Your Note added';
            return $this->outputer->code(200)->success($return)->json();


        }catch (\Exception $e) {

            DB::rollBack();
            $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => $e->getMessage()
                    ]
                ];
            return $result;
        }
    }

    public function MyTags(Request $request){

        try {

            $user_id = Auth::user()->id;

            // $user_tags = Tag::select('id','tag_name')
            //             ->where('user_id',$user_id)
            //             ->where('status',1)
            //             ->get();

            $user_tags = Cache::remember("user_tags_$user_id", 300, function () use ($user_id) {
                return Tag::select('id', 'tag_name')
                    ->where('user_id', $user_id)
                    ->where('status', 1)
                    ->get();
            });

            if(empty($user_tags)) {
                
                $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => "Empty user tags "
                    ]
                ];
                return $result;
            }

            return $this->outputer->code(200)
                        ->success($user_tags)->json();

        }catch (\Exception $e) {

            $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => $e->getMessage()
                    ]
                ];
            return $result;
        }
    }

    public function AddTag(Request $request){
        
        DB::beginTransaction();

        try {

            $user_id = Auth::user()->id;

            $a =  $request->validate([
                    'tag_name'  => 'required',
                ]);

            $inputData['user_id'] = $user_id;
            $inputData['tag_name'] = $request['tag_name'];

            $tag = Tag::create($inputData);
            DB::commit();

            Cache::forget("user_tags_$user_id");

            Cache::remember("user_tags_$user_id", 300, function () use ($user_id) {
                return Tag::select('id', 'tag_name')
                    ->where('user_id', $user_id)
                    ->where('status', 1)
                    ->get();
            });

            $return['messsage']  =  'Success.Your Tag Saved';
            return $this->outputer->code(200)->success($return)->json();


        }catch (\Exception $e) {

            DB::rollBack();
            $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => $e->getMessage()
                    ]
                ];
            return $result;
        }
    }

    public function DeleteTag(Request $request){
        
        DB::beginTransaction();

        try {

            $user_id = Auth::user()->id;

            $tag =Tag::where('id',$request->id)->where('user_id',$user_id)->first();
            if($tag){

                $exists = UserBibleMarking::where('type', 2)->where('user_id',$user_id)->value('data') && in_array($request->id, explode(',', UserBibleMarking::where('type', 2)->where('user_id',$user_id)->value('data')));
                if(!$exists) {
                    $gQ =UserCustomNote::where('tag_id',$request->id)->where('user_id',$user_id)->first();
                    if(!$gQ){
                        $tag->delete();
                        DB::commit();
                        $return['messsage']  =  'Success.Your Tag Removed';
                    }else{
                        $return['messsage']  =  'Failed.Tag deletion not allowed';
                    }
                }else{
                    $return['messsage']  =  'Failed.Tag deletion not allowed';
                }
            }else{
                $return['messsage']  =  'Failed.Your Tag Not Removed';
            }
            return $this->outputer->code(200)->success($return)->json();

         }catch (Exception $e) {

            DB::rollBack();
            $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => $e->getMessage()
                    ]
                ];
            return $result;

        }
    }

    public function MyBibleMarkings(Request $request){

        try {

            $user_id = Auth::user()->id;

            $user_notes = UserBibleMarking::select('id','user_id','type','statement_id','data')
                        ->where('user_id',$user_id)
                        ->where('type',1)
                        ->where('status',1)
                        ->orderBy('statement_id')
                        ->get()->makeHidden(['type_name','user_name']);
            if(empty($user_notes)) {
               
                $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => "Empty notes "
                    ]
                ];
                return $result;
            }
            $user_notes->transform(function ($item, $key) {

                $verse = HolyStatement::where('statement_id',$item->statement_id)->first();
                $item->verse_statement = $verse['statement_text'];
                $item->marked_data = [$item->data];
                $item->chapter_no = $verse->chapter_no;
                $item->statement_no = $verse->statement_no;

                return $item;
            });


            $user_tags = UserBibleMarking::select('id','user_id','type','statement_id','data')
                        ->where('user_id',$user_id)
                        ->where('type',2)
                        ->where('status',1)
                        ->orderBy('statement_id')
                        ->get()->makeHidden(['type_name','user_name']);
            if(empty($user_tags)) {
                
                $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => "Empty bookmarks "
                    ]
                ];
                return $result;
            }
            $user_tags->transform(function ($item, $key) {

                $verse = HolyStatement::where('statement_id',$item->statement_id)->first();
                $item->verse_statement = $verse['statement_text'];

                $tagIds = explode(',', $item->data);
                $tagNames = Tag::whereIn('id', $tagIds)->pluck('tag_name')->toArray();
                $item->marked_data = $tagNames;
                $item->chapter_no = $verse->chapter_no;
                $item->statement_no = $verse->statement_no;

                return $item;
            });


            $user_colors = UserBibleMarking::select('id','user_id','type','statement_id','data')
                        ->where('user_id',$user_id)
                        ->where('type',3)
                        ->where('status',1)
                        ->orderBy('statement_id')
                        ->get()->makeHidden(['type_name','user_name']);
            if(empty($user_colors)) {
                
                $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => "Empty color markings "
                    ]
                ];
                return $result;
            }
            $user_colors->transform(function ($item, $key) {

                $verse = HolyStatement::where('statement_id',$item->statement_id)->first();
                $item->verse_statement = $verse['statement_text'];
                $item->marked_data = [$item->data];
                $item->chapter_no = $verse->chapter_no;
                $item->statement_no = $verse->statement_no;

                return $item;
            });


            $mergedData = [
                [ 'category' => 'Notes', 'list' => $user_notes ],

                [ 'category' => "Tags", 'list' => $user_tags ],

                [ 'category' => 'Highlghts', 'list' => $user_colors ] 
            ];

            return $this->outputer->code(200)
                        ->success($mergedData )
                        ->json();

        }catch (\Exception $e) {

            $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => $e->getMessage()
                    ]
                ];
            return $result;
        }
    }

    public function MyBibleMarkingsV2(Request $request){

        try {

            $user_id = Auth::user()->id;


            /* ----------User Custom Notes---------*/

            $user_custom_notes = Tag::select('id','tag_name as data',DB::raw("Null as verse_statement"),
                                DB::raw("Null as chapter_no"),DB::raw("Null as statement_no"),
                                DB::raw("Null as book_name"))
                        ->where('user_id', $user_id)
                        ->where('status', 1)
                        ->get();

            $user_custom_notes = $user_custom_notes->filter(function ($item) use ($user_id) {
                $allnotes = $item->getCustomNotes($user_id);
                return !empty($allnotes);
            });

            if ($user_custom_notes->isEmpty()) {
                $return['result'] = "Empty tags";
            }

            $user_custom_notes->transform(function ($item, $key) use ($user_id) {
                
                $item->marked_data = [];
                $allnotes = $item->getCustomNotes($user_id);
                $item->verse_list = array_map(function ($note) {
                    return [
                        'id' => $note->id ?? null,
                        'data1' => $note->note_text ?? null,
                        'data2' => null,
                        'data3' => null,
                        'data4' => null,
                    ];
                }, $allnotes);

                return [
                    'id' => $item->id,
                    'data' => $item->data,
                    'verse_statement' => $item->verse_statement,
                    'chapter_no' => $item->chapter_no,
                    'statement_no' => $item->statement_no,
                    'book_name' => $item->book_name,
                    'marked_data' => $item->marked_data,
                    'verse_list' => $item->verse_list,
                ];
            });

            $user_custom_notes = $user_custom_notes->values()->all();

            /* ----------User Bible Notes---------*/


            $user_notes = UserBibleMarking::select('id','statement_id','data')
                        ->where('user_id',$user_id)
                        ->where('type',1)
                        ->where('status',1)
                        ->orderBy('statement_id')
                        ->get()
                        ->makeHidden(['type_name','user_name','chapter_name','testament_name',
                            'bible_name','verse_no']);
            if(empty($user_notes)) {
                $return['result']=  "Empty Bible notes ";
            }
            $user_notes->transform(function ($item, $key) {

                $verse = HolyStatement::where('statement_id',$item->statement_id)->first();
                $item->verse_statement = $verse['statement_text'];
                $item->marked_data = [$item->data];
                $item->chapter_no = $verse->chapter_no;
                $item->statement_no = $verse->statement_no;
                $item->verse_list =[];

                return $item;
            });


           /* ----------User Tags---------*/


            $user_tags =  Tag::select('id','tag_name as data',DB::raw("Null as verse_statement"),DB::raw("Null as chapter_no"),DB::raw("Null as statement_no"),
                                DB::raw("Null as book_name"))
                            ->where('user_id',$user_id)->where('status',1)->get();
            $user_tags = $user_tags->filter(function ($item) use ($user_id) {
                $statementIds = $item->getBibleMarkings($user_id);
                return !empty($statementIds);
            });

            if(empty($user_tags)) {
                $return['result']=  "Empty tags ";
            }

            // $user_tags = $user_tags->map(function ($item) use ($user_id) {
            //     $statementIds = $item->getBibleMarkings($user_id);
            //     $statementIdsValues = array_values($statementIds);
            //     dd($statementIds,$statementIdsValues);
            //     $holyStatements = HolyStatement::whereIn('statement_id', $statementIds)
            //                                     ->with('Book', 'Chapter')
            //                                     ->get();

            //     $verses = $holyStatements->map(function ($verse) use ($item){
            //             return [
            //                 'id' => $verse->statement_id,
            //                 'data1' => $verse->statement_text,
            //                 'data2' => $verse->Book->book_name ?? null,
            //                 'data3' => $verse->Chapter->chapter_no ?? null,
            //                 'data4' => $verse->statement_no,
            //             ];
            //     });

            //     return [
            //         'id' => $item->id,
            //         'data' => $item->data,
            //         'verse_statement' => $item->verse_statement,
            //         'chapter_no' => $item->chapter_no,
            //         'statement_no' => $item->statement_no,
            //         'book_name' => $item->book_name,
            //         'marked_data' => [],
            //         'verse_list' => $verses->toArray(),
            //     ];
            
            // })->values();


            $user_tags = $user_tags->map(function ($item) use ($user_id) {
                $statementIds = $item->getBibleMarkings($user_id);
                $statementIdsValues = array_values($statementIds);
                $statementKeyMap = array_flip($statementIds);

                $holyStatements = HolyStatement::whereIn('statement_id', $statementIdsValues)
                    ->with('Book', 'Chapter')
                    ->get();

                $verses = $holyStatements->map(function ($verse) use ($statementKeyMap) {
                    $key = $statementKeyMap[$verse->statement_id] ?? null;

                    return [
                        'id' => $key,
                        'data1' => $verse->statement_text,
                        'data2' => $verse->Book->book_name ?? null,
                        'data3' => $verse->Chapter->chapter_no ?? null,
                        'data4' => $verse->statement_no,
                        'data5' => $verse->statement_id,
                    ];
                });

                // Build the final structure for each tag
                return [
                    'id' => $item->id,
                    'data' => $item->data,
                    'verse_statement' => $item->verse_statement,
                    'chapter_no' => $item->chapter_no,
                    'statement_no' => $item->statement_no,
                    'book_name' => $item->book_name,
                    'marked_data' => [],
                    'verse_list' => $verses->toArray(),
                ];
            })->values();


            /* ----------User Colors---------*/


            $user_colors = UserBibleMarking::select('id','statement_id','data')
                        ->where('user_id',$user_id)
                        ->where('type',3)
                        ->where('status',1)
                        ->orderBy('statement_id')
                        ->get()
                        ->makeHidden(['type_name','user_name','chapter_name','testament_name',
                            'bible_name','verse_no']);
            if(empty($user_colors)) {
                $return['result']=  "Empty color markings ";
            }
            $user_colors->transform(function ($item, $key) {

                $verse = HolyStatement::where('statement_id',$item->statement_id)->first();
                $item->verse_statement = $verse['statement_text'];
                $item->marked_data = [$item->data];
                $item->chapter_no = $verse->chapter_no;
                $item->statement_no = $verse->statement_no;
                $item->verse_list = [];

                return $item;
            });

            $mergedData = [

                [ 'category' => 'Custom Notes', 'list' => $user_custom_notes ],

                [ 'category' => 'Bible Notes', 'list' => $user_notes ],

                [ 'category' => "Tags", 'list' => $user_tags ],

                [ 'category' => 'Highlights', 'list' => $user_colors ] 
            ];

            return $this->outputer->code(200)
                        ->success($mergedData )
                        ->json();

        }catch (\Exception $e) {

            $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => $e->getMessage()
                    ]
                ];
            return $result;
        }
    }

    public function AddBibleMarking(Request $request){
        
        DB::beginTransaction();

        try {

            $user_id = Auth::user()->id;

            $a =  $request->validate([
                    'type'          => 'required',
                    'statement_ids' => 'required',
                    'data'          => 'required',
                ]);
            
            $statementIdsArray = explode(',', $request['statement_ids']);

            foreach($statementIdsArray as $key=>$value){

                $user_prev_marking = UserBibleMarking::where('user_id',$user_id)
                                ->where('type',$request['type'])->where('statement_id',$value)->first();
                if($user_prev_marking){
                    $return['messsage']  =  'FAILED.Some of the stament is already marked';
                    return $this->outputer->code(200)->success($return)->json();
                }else{
                    $inputData['user_id'] = $user_id;
                    $inputData['type'] = $request['type'];
                    $inputData['statement_id'] = $value;
                    $inputData['data'] = $request['data'];

                    $marking = UserBibleMarking::create($inputData);
                    
                }

                DB::commit();
            }

            $return['messsage']  =  'Success.Your Marking Saved';
            return $this->outputer->code(200)->success($return)->json();


        }catch (\Exception $e) {

            DB::rollBack();
            $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => $e->getMessage()
                    ]
                ];
            return $result;
        }
    }

    public function DeleteBibleMarking(Request $request){
        
        DB::beginTransaction();

        try {

            $user_id = Auth::user()->id;

            if($request->category=='Custom Notes'){
                $note =UserCustomNote::where('id',$request->id)->where('user_id',$user_id)->first();
                if($note){
                    $note->delete();
                    DB::commit();
                    $return['messsage']  =  'Success.Your Custom note Removed';
                }else{
                    $return['messsage']  =  'Failed.Custom note is not available';
                }
            }else{

                $marking =UserBibleMarking::where('id',$request->id)
                            ->where('user_id',$user_id)->first();
                if($marking){
                    $marking->delete();
                    DB::commit();
                    $return['messsage']  =  'Success.Your marking Removed';
                }else{
                    $return['messsage']  =  'Failed.Your marking Not Removed';
                }
            }
            return $this->outputer->code(200)->success($return)->json();

         }catch (Exception $e) {

            DB::rollBack();
            $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => $e->getMessage()
                    ]
                ];
            return $result;

        }
    }

    public function ClearCache(Request $request) {
        
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('config:cache');
        Artisan::call('view:clear');
        return "Cleared!";
    }
}
