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

use App\Models\Notification;

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
            ->of(Notification::select('*')->where('status',1))
            ->addColumn('image', function ($notification) {

                if($notification->image) {
                    return '<img  class="img-70 rounded-circle" src="' . asset($notification->image) . '"  alt="notification" style="height: 70px;">';
                }else{
                    return '<img  class="img-70 rounded-circle" src="' . asset('storage/others/no_image.jpg') . '"  alt="notification" style="height: 70px;">';

                }
            })
            ->addColumn('action', 'notifications.datatable-action')
            ->rawColumns(['image','action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('notifications.Notification');
    }

    public function AddNotification() : View
    {
        return view('notifications.AddNotification',[]);
    }

    public function StoreNotification(Request $request): RedirectResponse
    {

        DB::beginTransaction();
        try {

            $data =  $request->validate([
                'title' => 'required',
            ]);

            $inputData = $request->all();

            if($request['image']){

                $fileName = str_replace(' ', '_', $request->title).'.'.$request['image']->extension();

                $request->image->storeAs('notification', $fileName);
                $inputData['image'] = 'storage/notification/'.$fileName;
            }
            $Notification = Notification::create($inputData);
            DB::commit();
             
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

        return view('notifications.EditNotification',compact('notification'));

    }

    public function UpdateNotification(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            
            $notification = Notification::find($request->id);

            $data =  $request->validate([
                'title' => 'required',
            ]);

            $inputData = $request->all();

            if($request['image']){

                $fileName = str_replace(' ', '_', $request->title).'.'.$request['image']->extension();

                $request->image->storeAs('notification', $fileName);
                $inputData['image'] = 'storage/notification/'.$fileName;
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

}
