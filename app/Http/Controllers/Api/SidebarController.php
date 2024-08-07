<?php

namespace App\Http\Controllers\Api;

use DB;
use Mail;
use Auth;
use Artisan;
use Carbon\Carbon;

use App\Models\GotQuestion;
use App\Models\GQCategory;
use App\Models\GQSubCategory;
use App\Models\UserQNA;

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
                $return['result']=  "Empty blood group list ";
                return $this->outputer->code(422)->error($return)->json();
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

            $return['result']=$e->getMessage();
            return $this->outputer->code(422)->error($return)->json();
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
                $return['result']=  "Empty blood group list ";
                return $this->outputer->code(422)->error($return)->json();
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

            $return['result']=$e->getMessage();
            return $this->outputer->code(422)->error($return)->json();
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
                $return['result']=  "Empty blood group list ";
                return $this->outputer->code(422)->error($return)->json();
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

            $return['result']=$e->getMessage();
            return $this->outputer->code(422)->error($return)->json();
        }
    }

    public function AskedQuestions(Request $request){

        try {

            $pg_no='';
            $per_pg=100;

            $user_id = Auth::user()->id;

            $user_asked_qna = UserQNA::select('*')
                    //->where('user_id',$user_id)
                    ->where('status',1);

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
                $return['result']=  "Empty blood group list ";
                return $this->outputer->code(422)->error($return)->json();
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

            $return['result']=$e->getMessage();
            return $this->outputer->code(422)->error($return)->json();
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
            $return['result']=$e->getMessage();
            return $this->outputer->code(422)->error($return)->json();
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
