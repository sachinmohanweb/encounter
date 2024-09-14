<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

use DB;
use Session;
use Exception;
use Datatables;

use App\Models\GotQuestion;
use App\Models\GQCategory;
use App\Models\GQSubCategory;

class GotQuestionController extends Controller
{
    
    public function GotQuestion() : View
    {
        return view('got_questions.gotQuestion',[]);
    }

    public function gq_category_list(Request $request): JsonResponse
    {
        $searchTerm = $request->input('search_tag');

        $GQ_Categories = GQCategory::where('name', 'like',  $searchTerm . '%')
                        ->get(['id', 'name']);
        $results = [];

        foreach ($GQ_Categories as $GQ_Category) {
            $results[] = [
                'id' => $GQ_Category->id,
                'text' => $GQ_Category->name,
            ];
        }
        return response()->json(['results' => $results]);
    }

    public function gq_subcategory_list(Request $request): JsonResponse
    {
        $searchTerm = $request->input('search_tag');

        $GQ_Subcategories = GQSubCategory::where('name', 'like',  $searchTerm . '%');

        if($request['category_id']){
            $GQ_Subcategories = $GQ_Subcategories->where('cat_id',$request['category_id']);
        }
        $GQ_Subcategories = $GQ_Subcategories->get(['id', 'name']);

                        
        $results = [];

        foreach ($GQ_Subcategories as $GQ_Subcategory) {
            $results[] = [
                'id' => $GQ_Subcategory->id,
                'text' => $GQ_Subcategory->name,
            ];
        }
        return response()->json(['results' => $results]);
    }

    public function QotQuestionDatatable()
    {
        if(request()->ajax()) {
            return datatables()
            ->of(GotQuestion::select('*')->where('status',1))
            ->addColumn('category', function ($gq) {
                return  $gq->category_name;
            })
             ->addColumn('sub_category', function ($gq) {
                return  $gq->sub_category_name;
            })
            ->addColumn('question', function ($gq) {
                $truncatedqt = Str::limit($gq->question, 45); // Show only 50 characters
                return $truncatedqt . ' <a href="javascript:void(0);" class="view-more" data-answer="'.htmlspecialchars($gq->question).'">View More</a>';
            })
            ->addColumn('answer', function ($gq) {
                $truncatedAnswer = Str::limit($gq->answer, 45); // Show only 50 characters
                return $truncatedAnswer . ' <a href="javascript:void(0);" class="view-more" data-answer="'.htmlspecialchars($gq->answer).'">View More</a>';
            })
            ->addColumn('action', 'got_questions.datatable-action')
            ->rawColumns(['question','category','sub_category','answer','action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('got_questions.gotQuestion');
    }

    public function AddGotQuestion() : View
    {
        return view('got_questions.gotQuestionAnswer',[]);
    }

    public function StoreGotQuestion(Request $request): RedirectResponse
    {

        DB::beginTransaction();
        try {

            $data =  $request->validate([
                'question' => 'required',
                'category_id' => 'required',
                'sub_category_id' => 'required',
                'answer' => 'required',
            ]);

            $inputData = $request->all();

            $Got_Question = GotQuestion::create($inputData);
            DB::commit();
             
            return redirect()->route('admin.got-question')
                            ->with('success',"Success! Your Question and answer has been successfully added.");
        }catch (Exception $e) {

            DB::rollBack();
            $message = $e->getMessage();
            return back()->withInput()->withErrors(['message' =>  $e->getMessage()]);;
        }
    }

    public function EditGotQuestion($id) : View
    {
        $Got_Question = GotQuestion::where('id',$id)->first();

        $GQ_Category = GQCategory::all();
        $GQ_SubCategory = GQSubCategory::all();

        return view('got_questions.gotQuestionEdit',compact('Got_Question','GQ_Category','GQ_SubCategory'));

    }

    public function UpdateGotQuestion(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            
            $Got_Question = GotQuestion::find($request->id);

            $data =  $request->validate([
                'question' => 'required',
                'category_id' => 'required',
                'sub_category_id' => 'required',
                'answer' => 'required',
            ]);

            $inputData = $request->all();

            $Got_Question->update($inputData);


            DB::commit();

           return redirect()->route('admin.got-question')
                            ->with('success',"Success! Your Question and answer has been successfully Updated.");
        }catch (Exception $e) {

            DB::rollBack();
            $message = $e->getMessage();
            return back()->with('error',$e->getMessage());
        }
    }

    public function DeleteGotQuestion(Request $request) : JsonResponse
    {
        DB::beginTransaction();
        try{

            $Got_Question =GotQuestion::where('id',$request->id)->first();
            if($Got_Question){
                $Got_Question->delete();
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

    public function GQ_Categories() : View
    {
        return view('got_questions.categories',[]);
    }

    public function GQCategoriesDatatable()
    {
        if(request()->ajax()) {
            return datatables()
            ->of(GQCategory::select('*'))
            ->addColumn('action', 'got_questions.category_datatable-action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('got_questions.categories');
    }

    public function StoreGQCategory(Request $request): RedirectResponse
    {

        DB::beginTransaction();
        try {

            $data =  $request->validate([
                'name' => 'required',
            ]);

            $inputData = $request->all();

            $gq_cat = GQCategory::create($inputData);
            DB::commit();
             
            return redirect()->route('admin.gq.categories')
                            ->with('success',"Success! Category has been successfully added.");
        }catch (Exception $e) {

            DB::rollBack();
            $message = $e->getMessage();
            return back()->withInput()->withErrors(['message' =>  $e->getMessage()]);;
        }
    }

    public function DeleteGQCategory(Request $request) : JsonResponse
    {
        DB::beginTransaction();
        try{
            $cat =GQCategory::where('id',$request->id)->first();
            if($cat){
                $cat->delete();
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

    public function GQ_Sub_Categories() : View
    {
        return view('got_questions.subcategories',[]);
    }

    public function GQSubCategoriesDatatable()
    {
        if(request()->ajax()) {
            return datatables()
            ->of(GQSubCategory::select('*'))
            ->addColumn('category', function ($gq) {
                return  $gq->Category->name;
            })
            ->addColumn('action', 'got_questions.subcategory_datatable-action')
            ->rawColumns(['category','action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('got_questions.subcategories');
    }

    public function StoreGQSubCategory(Request $request): RedirectResponse
    {

        DB::beginTransaction();
        try {

            $data =  $request->validate([
                'cat_id' => 'required',
                'name' => 'required',
            ]);

            $inputData = $request->all();

            $gq_cat = GQSubCategory::create($inputData);
            DB::commit();
             
            return redirect()->route('admin.gq.subcategories')
                            ->with('success',"Success! Subcategory has been successfully added.");
        }catch (Exception $e) {

            DB::rollBack();
            $message = $e->getMessage();
            return back()->withInput()->withErrors(['message' =>  $e->getMessage()]);;
        }
    }

    public function DeleteGQSubCategory(Request $request) : JsonResponse
    {
        DB::beginTransaction();
        try{
            $cat =GQSubCategory::where('id',$request->id)->first();
            if($cat){
                $cat->delete();
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
 
}
