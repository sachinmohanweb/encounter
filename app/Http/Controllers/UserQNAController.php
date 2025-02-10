<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

use DB;
use Session;
use Exception;

use App\Models\User;
use App\Models\UserQNA;

use App\Notifications\NotificationPusher; 
use App\Jobs\SendPushNotification;

class UserQNAController extends Controller
{
    
    public function UserQNAList() : View
    {
        $User_QNA = UserQNA::orderBy('created_at','desc')->get();
        return view('user_qna.user_qna_list',compact('User_QNA'));
    }

    public function UserQNADetails($id) : View
    {
        $User_QNA = UserQNA::where('id',$id)->first();
        return view('user_qna.UserQNADetail',compact('User_QNA'));

    }

    public function UserQNADetailsModal($id) : JsonResponse
    {
        $User_QNA = UserQNA::where('id',$id)->first();

        if ($User_QNA) {
            return response()->json([
                'id' => $User_QNA->id,
                'question' => $User_QNA->question,
                'answer' => $User_QNA->answer
            ]);
        } else {
            return response()->json(['error' => 'QNA not found'], 404);
        }
    }

    public function UpdateUserQNAAnswer(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {

            $User_QNA = UserQNA::find($request->user_qna_id);

            $a =  $request->validate([
                'user_qna_answer' => 'required',
            ]);

            $inputData['answer'] = $request->user_qna_answer;
            $inputData['status'] = 2;
            $User_QNA->update($inputData);

            DB::commit();
            
            $push_data = [];

            $push_data['tokens']    =  User::where('id',$User_QNA->user_id)
                                            ->whereNotNull('refresh_token')
                                            ->pluck('refresh_token')->toArray();

            $push_data['title']         =   'User QNA Anser Updated';
            $push_data['body']          =   'Question :  '.$User_QNA->question ;

            $push_data['route']         =   'UserQNA';
            $push_data['id']            =   $User_QNA['id'];
            $push_data['category']      =   'UserQNA';

            $push_data['data1']         =   $User_QNA->user_name;
            $push_data['data2']         =   $User_QNA->question;
            $push_data['data3']         =   $User_QNA->answer;
            $push_data['data4']         =   null;
            $push_data['data5']         =   null;
            $push_data['image1']        =   null;

            if (!empty($push_data['tokens'])) {
                if(env('QUEUE_CONNECTION') === 'sync') {
                    $pusher = new NotificationPusher();
                    $pusher->push($push_data);
                }else{
                    SendPushNotification::dispatch($push_data)->onQueue('push-notifications');
                }
            }


            return redirect()->route('admin.user_qna.details',['id' => $request->user_qna_id])
                            ->with('success',"Success! Answer for this question has been successfully updated.");
        }catch (Exception $e) {

            DB::rollBack();
            $message = $e->getMessage();
            return back()->withInput()->withErrors(['message' =>  $e->getMessage()]);;
        }
    }
 
}
