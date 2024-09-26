<?php

namespace App\Http\Controllers\Api;

use DB;
use Mail;
use Auth;
use Carbon\Carbon;

use App\Models\User;
use App\Models\EmailVerification;


use App\Mail\UserVerificationMail;
use App\Http\Repositories\UserRepository;

use App\Helpers\Outputer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;

use App\Http\Controllers\Controller;
class UserController extends Controller
{
    public function __construct(Outputer $outputer,UserRepository $userRepo){

        $this->outputer = $outputer;
        $this->userRepo = $userRepo;
    }
    
    public function Signup(Request $request){
        DB::beginTransaction();

        try {

            $a =  $request->validate([
                    'first_name'    => 'required',
                    'email'         => 'required|email|unique:users,email',
                ]);

            $inputData['first_name'] = $request['first_name'];
            $inputData['email'] = $request['email'];

            $user = User::create($inputData);
            DB::commit();

            $return['messsage']  =  'success';
            $return['user']  =  $user;
            return $this->outputer->code(200)->success($return)->json();


        }catch (\Exception $e) {

            DB::rollBack();
            $return['result']=$e->getMessage();
            return $this->outputer->code(422)->error($return)->json();
        }
    }

    public function loginUser(Request $request){

        DB::beginTransaction();

        try {
            $user = $this->userRepo->checkUser($request->all());
            if(empty($user)) {
                $return['result']=  "Invalid/Incorrect Email Address.";
                return $this->outputer->code(422)->error($return)->json();
            }else{
                $otp = mt_rand(1000, 9999);

                $inputData['email'] = $request['email'];
                $inputData['otp'] = $otp;
                $inputData['otp_expiry'] = Carbon::now()->addMinutes(5);

                EmailVerification::create($inputData);
                DB::commit();

                $user = User::where('email',$request->input('email'))->first();

                
                $mailData = [
                    'user' => $user,
                    'otp' => $otp,
                ];

                Mail::to($request->input('email'))->send(new UserVerificationMail($mailData));

                $return['messsage']  =  'OTP sent to your email';
                return $this->outputer->code(200)->success($return)->json();
            }

        }catch (\Exception $e) {

            DB::rollBack();
            $return['result']=$e->getMessage();
            return $this->outputer->code(422)->error($return)->json();
        }
    }

    public function VerifyOtp(Request $request){

        DB::beginTransaction();

        try {
            
            $otp = EmailVerification::where('email', $request->email)
                ->where('otp', $request->otp)
                ->where('otp_used', false)
                ->first();

            if ($otp) {

                if (Carbon::now()->lt($otp->otp_expiry)) {
                    $otp->otp_used = true;
                    $otp->save();

                    $user = User::where('email',$request->email)->first();
                    
                    Auth::guard('users')->login($user);

                    if($request->device_id){

                        $user->device_id = $request->device_id;
                        $user->refresh_token = $request->refresh_token;
                        $user->save();
                    }

                    $token = $user->createToken('encounter-bible-app')->plainTextToken;

                    DB::commit();
                    
                    $return['messsage']  =  'OTP verified successfully';
                    $return['token']  = $token;
                    $return['user']  =  $user;

                    return $this->outputer->code(200)->success($return)->json();
                }else{
                    $return['messsage']  =  'OTP Expired';
                    return $this->outputer->code(422)->error($return)->json();
                }
            }else{

                $return['messsage']  =  'Invalid OTP';
                return $this->outputer->code(422)->error($return)->json();
            }

        }catch (\Exception $e) {

            DB::rollBack();
            $return['result']=$e->getMessage();
            return $this->outputer->code(422)->error($return)->json();
        }
    }
    
    public function myProfile(){

        DB::beginTransaction();

        try {

            $user=Auth::user();
            $profile = User::find($user['id']);

            return $this->outputer->code(200)->success($profile)->json();

        }catch (\Exception $e) {

            $return['result']=$e->getMessage();
            return $this->outputer->code(422)->error($return)->json();
        }
    }

    public function editProfile(Request $request){
        
        DB::beginTransaction();

        try {

            
            $logged_user=Auth::user();
            $user = User::find($logged_user['id']);

            $a =$request->validate([
                'first_name'    => 'required',
                'email' => ['required','email',Rule::unique('users', 'email')->ignore($user->id)],
            ]);

            $inputData = $request->all();

            if($request['last_name']){
                $inputData['last_name'] = $request['last_name'];
            }
            if($request['gender']){
                $inputData['gender'] = $request['gender'];
            }
            if($request['location']){
                $inputData['location'] = $request['location'];
            }
            if($request['age']){
                $inputData['age'] = $request['age'];
            }
            if($request['image']){

                $fileName = str_replace(' ', '_', $request->first_name).'_' . time() . '.' .$request['image']->extension();

                $request->image->storeAs('users', $fileName);
                $inputData['image'] = 'storage/users/'.$fileName;
            }

            $user->update($inputData);
            DB::commit();

            $return['messsage']  =  'success';
            $return['user']  =  $user;
            return $this->outputer->code(200)->success($return)->json();


        }catch (\Exception $e) {

            DB::rollBack();
            $return['result']=$e->getMessage();
            return $this->outputer->code(422)->error($return)->json();
        }
    }
    public function logoutuser(){

        DB::beginTransaction();

        try {

            $user=Auth::user();
            $currentToken = $user->currentAccessToken();

            if ($currentToken) {
                $currentToken->delete();
            }
            
            DB::commit();

            $return['messsage']  =  'User Tokens deleted';
            return $this->outputer->code(200)->success($return)->json();

        }catch (\Exception $e) {

            DB::rollBack();
            $return['result']=$e->getMessage();
            return $this->outputer->code(422)->error($return)->json();
        }
    }
}
