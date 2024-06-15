<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

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
            ->addColumn('action', 'got_questions.datatable-action')
            ->rawColumns(['category','sub_category','action'])
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
 
}
