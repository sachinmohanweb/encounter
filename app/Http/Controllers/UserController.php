<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

use DB;
use Auth;
use Exception;
use Datatables;

use App\Models\User;
use App\Models\UserNote;
use App\Models\UserLMS;

class UserController extends Controller
{
    
    public function admin_login(Request $request): RedirectResponse
    {

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = false;

        if($request['remember_password']){
            $remember = true;
        }

        if (Auth::guard('admin')->attempt($credentials,$remember)) {
            
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }
        else{

            return redirect()->route('index')->withErrors(['message' => 'Incorrect Credentials']);
        }
    }

    public function admin_logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();
     
        $request->session()->invalidate();
     
        $request->session()->regenerateToken();
     
        return redirect()->route('admin.dashboard')->with('success', 'You have been successfully logged out.');
    }

    public function UsersList() : View
    {
        return view('users.userProfile',[]);
    } 

    public function admin_users_Datatable()
    {
        if(request()->ajax()) {

            return datatables()->of(User::select('*'))
            ->addColumn('user_full_name', function ($user) {
                    return $user->first_name . ' ' . $user->last_name;
            })
            ->addColumn('action', 'users.datatable-action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('users.userProfile');
    }

    public function admin_user_status_change(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = User::find($request['id']);
            if($user->status==1){
                $user->status=2;
            }else{
                $user->status=1;
            }
            $user->save();;
            DB::commit();

            return response()->json(['success' => true ,'msg' => 'User status updated','status' =>$user['status']]);

        }catch (Exception $e) {

            DB::rollBack();
            $message = $e->getMessage();

            return response()->json(['success' => false,'msg' => $e->getMessage()]);
        }
    }

    public function UsersNotes() : View
    {
        return view('users.userNotes',[]);
    }

    public function UsersNotes_Datatable()
    {

        if(request()->ajax()) {
            return datatables()
            ->of(UserNote::select('*'))
            ->addColumn('user', function ($user_note) {
                return $user_note->user_name;

            })
            ->addColumn('bible', function ($user_note) {
                return $user_note->bible_name;
            })
            ->addColumn('testament', function ($user_note) {
                return $user_note->testament_name;
            })
            ->addColumn('book', function ($user_note) {
                return $user_note->book_name;
            })
            ->addColumn('chapter', function ($user_note) {
                return $user_note->chapter_name;
            })
            ->addColumn('verse', function ($user_note) {
                return $user_note->verse_no;
            })
            ->addColumn('action', 'users.user_notes_status_datatable-action')
            ->rawColumns(['user','bible','book','chapter','verse','action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('bible_verse.DailyBibleVerse');
    }

    public function UsersNotes_status_change(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = UserNote::find($request['id']);
            if($user->status==1){
                $user->status=2;
            }else{
                $user->status=1;
            }
            $user->save();;
            DB::commit();

            return response()->json(['success' => true ,'msg' => 'User status updated','status' =>$user['status']]);

        }catch (Exception $e) {

            DB::rollBack();
            $message = $e->getMessage();

            return response()->json(['success' => false,'msg' => $e->getMessage()]);
        }
    }

    public function UserLms() : View
    {
        return view('users.UserLms',[]);
    }

    public function UsersLMS_Datatable()
    {

        if(request()->ajax()) {
            return datatables()
            ->of(UserLMS::select('*'))
            ->addColumn('user', function ($user_lms) {
                return $user_lms->user_name;

            })
            ->addColumn('course', function ($user_lms) {
                return $user_lms->course_name;
            })
            ->addColumn('batch', function ($user_lms) {
                return $user_lms->batch_name;
            })
            ->addColumn('completed_status', function ($user_lms) {
                return $user_lms->completed_status_name;
            })
           
            ->addColumn('action', 'users.user_lms_status_datatable-action')
            ->rawColumns(['user','course','batch','completed_status','action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('bible_verse.DailyBibleVerse');
    }

    public function UsersLMS_status_change(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = UserLMS::find($request['id']);
            if($user->status==1){
                $user->status=2;
            }else{
                $user->status=1;
            }
            $user->save();;
            DB::commit();

            return response()->json(['success' => true ,'msg' => 'User status updated','status' =>$user['status']]);

        }catch (Exception $e) {

            DB::rollBack();
            $message = $e->getMessage();

            return response()->json(['success' => false,'msg' => $e->getMessage()]);
        }
    }

    
}
