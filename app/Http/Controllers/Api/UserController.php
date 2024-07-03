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
                    'first_name'      => 'required',
                    'gender'    => 'required',
                    'email'   => 'required|email|unique:users,email',
                ]);

            $inputData['first_name'] = $request['first_name'];
            $inputData['gender'] = $request['gender'];
            $inputData['email'] = $request['email'];

            $user = User::create($inputData);
            DB::commit();

            $return['messsage']  =  'success';
            $return['family']  =  $user;
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
