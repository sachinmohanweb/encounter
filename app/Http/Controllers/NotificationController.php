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

use App\Models\User;
use App\Models\Notification;
use App\Models\NotificationType;

//use App\Notifications\NotificationPusher; 

use App\Jobs\SendPushNotification;


class NotificationController extends Controller
{
    
    public function Notifications() : View
    {
        return view('notifications.Notification',[]);
    }

    public function NotificationsDatatable()
    {
        if(request()->ajax()) {
            return datatables()
            ->of(Notification::select('*')->where('status',1)->orderBy('created_at','desc'))

            ->addColumn('type', function ($notification) {

               return $notification->TypeData->type_name;
            })
            ->addColumn('data', function ($notification) {

                if($notification->type==1){
                    if($notification['data']) {
                        return '<img  class="img-70 rounded-circle" src="' . asset($notification->data) . '"  alt="notification" style="height: 70px;">';
                    }else{
                        return '<img  class="img-70 rounded-circle" src="' . asset('storage/others/no_image.jpg') . '"  alt="notification" style="height: 70px;">';
                    }
                }else{
                    return '<a href="' . $notification->data . '" target="_blank">View Link</a>';
                }
            })
            ->addColumn('action', 'notifications.datatable-action')
            ->rawColumns(['type','data','action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('notifications.Notification');
    }

    public function AddNotification() : View
    {
        $notification_type = NotificationType::all();
        return view('notifications.AddNotification',compact('notification_type'));
    }

    public function StoreNotification(Request $request): RedirectResponse
    {

        DB::beginTransaction();
        try {

            if($request->type==1 || $request->type==2){

                $inputs =  $request->validate([
                    'title' => 'required',
                    'type' => 'required',
                    'file' => 'required',
                ]);
            }else{
                $inputs =  $request->validate([
                    'title' => 'required',
                    'type' => 'required',
                    'data' => 'required',
                ]);
            }

            $inputData = $request->all();

            if($request->type==1 || $request->type==2){

                if($request['file']){

                    $fileName = str_replace(' ', '_', $request->title) . '_' . now()->format('YmdHis') . '.' . $request['file']->extension();

                    $request->file->storeAs('notification', $fileName);
                    $inputData['data'] = 'storage/notification/'.$fileName;
                }
            }

            $Notification = Notification::create($inputData);
            DB::commit();
             
            $push_data = [];

            $push_data['tokens']    =  User::whereNotNull('refresh_token')
                                            ->pluck('refresh_token')->toArray();

            $push_data['title']         =   'Don’t miss out';
            $push_data['body']          =   'New Notification:'.$Notification['title'];

            $push_data['route']         =   'NewNotification';
            $push_data['id']            =   $Notification['id'];
            $push_data['category']      =   'NewNotification';

            $push_data['data1']         =   $Notification['redirection'];
            $push_data['data2']         =   $Notification['description'];
            $push_data['data3']         =   $Notification['type'];
            $push_data['data4']         =   $Notification['data'];
            $push_data['data5']         =   null;
            $push_data['image1']        =   null;

            if (!empty($push_data['tokens'])) {
                // $pusher = new NotificationPusher();
                // $pusher->push($push_data);

                SendPushNotification::dispatch($push_data)->onQueue('push-notifications');

            }

            return redirect()->route('admin.notification.list')
                            ->with('success',"Success! Notification has been successfully added.");
        }catch (Exception $e) {

            DB::rollBack();
            $message = $e->getMessage();
            return back()->withInput()->withErrors(['message' =>  $e->getMessage()]);;
        }
    }

    public function EditNotification($id) : View
    {
        $notification = Notification::where('id',$id)->first();
        $notification_type = NotificationType::all();

        return view('notifications.EditNotification',compact('notification','notification_type'));
    }

    public function UpdateNotification(Request $request): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $notification = Notification::find($request->id);

            if($request->type==1 || $request->type==2){

                $inputs =  $request->validate([
                    'title' => 'required',
                    'type' => 'required',
                ]);
            }else{

                $inputs =  $request->validate([
                    'title' => 'required',
                    'type' => 'required',
                ]);
            }

            $inputData = $request->all();

            if($request->type==1 || $request->type==2){

                if($request['file']){

                    $fileName = str_replace(' ', '_', $request->title) . '_' . now()->format('YmdHis') . '.' . $request['file']->extension();

                    $request->file->storeAs('notification', $fileName);
                    $inputData['data'] = 'storage/notification/'.$fileName;
                }
            }

            $notification->update($inputData);

            DB::commit();

           return redirect()->route('admin.notification.list')
                            ->with('success',"Success! Notification has been successfully Updated.");
        }catch (Exception $e) {

            DB::rollBack();
            $message = $e->getMessage();
            return back()->with('error',$e->getMessage());
        }
    }

    public function DeleteNotification(Request $request) : JsonResponse
    {
        DB::beginTransaction();
        try{
            $notification =Notification::where('id',$request->id)->first();
            if($notification){
                $notification->delete();
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

    public function gq_notification_type_list(Request $request): JsonResponse
    {
        $searchTerm = $request->input('search_tag');

        $types = NotificationType::where('type_name', 'like',  $searchTerm . '%')
                        ->get(['id', 'type_name']);
        $results = [];

        foreach ($types as $type) {
            $results[] = [
                'id' => $type->id,
                'text' => $type->type_name,
            ];
        }
        return response()->json(['results' => $results]);
    }

}
