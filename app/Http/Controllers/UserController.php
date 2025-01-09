<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

use DB;
use Auth;
use Exception;
use Datatables;

use App\Models\User;
use App\Models\UserCustomNote;
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

    public function password_change() : View
    {
        return view('users.admin_password_change',[]);
    } 

    public function password_update(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            
            $request->validate([
                'old_password' => ['required'],
                'new_password' => ['required', 'confirmed'],
            ]);

            $admin = Auth::guard('admin')->user();

            if (!Hash::check($request->old_password, $admin->password)) {
                return back()->withErrors(['old_password' => 'Old password is incorrect']);
            }

            $admin->password = Hash::make($request->new_password);
            $admin->save();
            DB::commit();

            Auth::guard('admin')->logout();
            
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('index')
                ->with('success', 'Password changed successfully. Please log in with your new password.');
        }catch (Exception $e) {

            DB::rollBack();
            $message = $e->getMessage();
            return back()->withInput()->withErrors(['message' =>  $e->getMessage()]);;
        }
    }

    public function admin_logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();
     
        $request->session()->invalidate();
     
        $request->session()->regenerateToken();
     
        return redirect()->route('hom_page')
                    ->with('success', 'You have been successfully logged out.');
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
             ->addColumn('image', function ($user) {

                if ($user->image) {
                    return '<img  class="img-70 rounded-circle" src="' . asset($user->image) . '"  alt="User Image" style="height: 70px;">';
                } else {
                    $nameWords = explode(' ', $user->first_name);
                    $nameLetters = '';

                    foreach ($nameWords as $word) {
                        $nameLetters .= substr($word, 0, 1);
                        if(strlen($nameLetters) >= 2) {
                            break;
                        }
                    }

                    if(strlen($nameLetters) == 1) {
                        //$nameLetters = substr($this->name, 0, 2);
                        $nameLetters = $nameLetters;
                    }

                    $backgroundColors = ['#3c95e5'];
                    $backgroundColor = $backgroundColors[array_rand($backgroundColors)];

                    return '<div class="img-70 rounded-circle text-center" style="height: 60px; width: 70px; background-color: ' . $backgroundColor . '; color: white; line-height: 60px; font-size: 24px;">' . $nameLetters . '</div>';
                }
            })
            ->addColumn('action', 'users.datatable-action')
            ->rawColumns(['image','action'])
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
            ->of(UserCustomNote::select('*'))
            ->addColumn('user', function ($user_note) {
                return $user_note->user_name;

            })
            ->addColumn('tag', function ($user_note) {
                return $user_note->tag_name;
            })
            ->addColumn('action', 'users.user_notes_status_datatable-action')
            ->rawColumns(['user','tag','action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('users.userNotes');
    }

    public function UsersNotes_status_change(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = UserCustomNote::find($request['id']);
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
