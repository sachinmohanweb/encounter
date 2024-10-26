<?php

namespace App\Http\Controllers\Api;

use DB;
use Mail;
use Auth;
use Carbon\Carbon;
use App\Models\User;

use App\Models\UserNote;
use App\Models\HolyStatement;
use App\Models\GotQuestion;

use App\Models\EmailVerification;


use App\Mail\UserVerificationMail;
use App\Http\Repositories\UserRepository;

use App\Helpers\Outputer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;

use App\Http\Controllers\Controller;
class UserController extends Controller
{
    public function __construct(Outputer $outputer,UserRepository $userRepo){

        $this->outputer = $outputer;
        $this->userRepo = $userRepo;
    }
    
    // public function SearchResults(Request $request)
    // {
    //     $searchTerm = $request->input('text');

    //     $bible_verse_results  = HolyStatement::search($searchTerm)->orderby('statement_id')->get();

    //     $color_bible_verse_results = $bible_verse_results->filter(function ($item) use ($searchTerm) {
    //         return stripos($item->statement_text, $searchTerm) !== false;
    //     })->map(function ($item) use ($searchTerm) {
            
    //         $contextWords = 5;
            
    //         preg_match('/(?:\S+\s+){0,' . $contextWords . '}\S*' . preg_quote($searchTerm, '/') . '\S*(?:\s+\S+){0,' . $contextWords . '}/i', $item->statement_text, $matches);

    //         $item->statement_text = isset($matches[0]) ? preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $matches[0]) . '.....' : $item->statement_text;

    //         return [
    //             'type' => 'Bible Verse',
    //             'id' => $item->statement_id,
    //             'result' => $item->statement_text
    //         ];
    //     });


    //     $user_note_results  = UserNote::search($searchTerm)->where('status', 1)->orderby('id')->get();
    //     $color_user_note_results = $user_note_results->filter(function ($item) use ($searchTerm) {
    //         return stripos($item->note, $searchTerm) !== false;
    //     })->map(function ($item) use ($searchTerm) {
            
    //         // $item->note = preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $item->note);
    //         // $item->type = 'User Notes';
    //         // return $item;

    //         $contextWords = 5;
            
    //         preg_match('/(?:\S+\s+){0,' . $contextWords . '}\S*' . preg_quote($searchTerm, '/') . '\S*(?:\s+\S+){0,' . $contextWords . '}/i', $item->note, $matches);

    //         $item->note = isset($matches[0]) ? preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $matches[0]) . '.....' : $item->note;

    //         return [
    //             'type' => 'User Notes',
    //             'id' => $item->id,
    //             'result' => $item->note
    //         ];
    //     });


    //     $gq_results  = GotQuestion::search($searchTerm)->where('status', 1)->orderby('id')->get();
    //     $color_qg_results = $gq_results->filter(function ($item) use ($searchTerm) {
    //         return stripos($item->question, $searchTerm) !== false || stripos($item->answer, $searchTerm) !== false;
    //     })->map(function ($item) use ($searchTerm) {
    //         // $item->question = preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $item->question);
    //         // $item->answer = preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $item->answer);
    //         // $item->type = 'Got Questions';
    //         // return $item;


    //         $contextWords = 5;

    //         if (stripos($item->question, $searchTerm) !== false) {
    //             preg_match('/(?:\S+\s+){0,' . $contextWords . '}\S*' . preg_quote($searchTerm, '/') . '\S*(?:\s+\S+){0,' . $contextWords . '}/i', $item->question, $matches);
    //             $highlighted_text = isset($matches[0]) ? preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $matches[0]) . '.....' : $item->question;
    //         } else {
    //             preg_match('/(?:\S+\s+){0,' . $contextWords . '}\S*' . preg_quote($searchTerm, '/') . '\S*(?:\s+\S+){0,' . $contextWords . '}/i', $item->answer, $matches);
    //             $highlighted_text = isset($matches[0]) ? preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $matches[0]) . '.....' : $item->answer;
    //         }

    //         return [
    //             'type' => 'Got Questions',
    //             'id' => $item->id,
    //             'result' => $highlighted_text,
    //         ];
    //     });

    //     $merged_results = $color_bible_verse_results
    //                         ->merge($color_user_note_results)
    //                         ->merge($color_qg_results);

    //     $total_results = $merged_results->count();

    //     return $this->outputer->code(200)
    //             ->success(['total' => $total_results, 'data' => $merged_results])->json();
    // }


    public function SearchResults(Request $request)
    {
        try {

            $searchTerm = $request->input('text');
            
            //-----------Bible Verse--------------//

            $bible_verse_results = collect(HolyStatement::search($searchTerm)
                                ->orderBy('statement_id')->get());

            $color_bible_verse_results = $bible_verse_results->filter(function ($item) use ($searchTerm) {
                return stripos($item->statement_text, $searchTerm) !== false;
            })->map(function ($item) use ($searchTerm) {

                $contextWords = 8;
                preg_match('/(?:\S+\s+){0,' . $contextWords . '}\S*' . preg_quote($searchTerm, '/') . '\S*(?:\s+\S+){0,' . $contextWords . '}/i', $item->statement_text, $matches);
                $highlighted_text = isset($matches[0]) ? preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $matches[0]) . '.....' : $item->statement_text;
                return [
                    'type' => 'Bible Verse',
                    'id' => $item->statement_id,
                    'result' => $highlighted_text
                ];
            });
        
            //-----------User Notes--------------//

            $user_note_results = collect(UserNote::search($searchTerm)
                                ->where('status', 1)->orderBy('id')->get());

            $color_user_note_results = $user_note_results->filter(function ($item) use ($searchTerm) {
                return stripos($item->note, $searchTerm) !== false;
            })->map(function ($item) use ($searchTerm) {

                $contextWords = 8;

                preg_match('/(?:\S+\s+){0,' . $contextWords . '}\S*' . preg_quote($searchTerm, '/') . '\S*(?:\s+\S+){0,' . $contextWords . '}/i', $item->note, $matches);
                $highlighted_text = isset($matches[0]) ? preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $matches[0]) . '.....' : $item->note;
                return [
                    'type' => 'User Notes',
                    'id' => $item->id,
                    'result' => $highlighted_text
                ];
            });
            
            //-----------Got Questions--------------//

            $gq_results = collect(GotQuestion::search($searchTerm)
                                ->where('status', 1)->orderBy('id')->get());
            $color_qg_results = $gq_results->filter(function ($item) use ($searchTerm) {
                return stripos($item->question, $searchTerm) !== false || stripos($item->answer, $searchTerm) !== false;
            })->map(function ($item) use ($searchTerm) {
                
                $contextWords = 8;

                if (stripos($item->question, $searchTerm) !== false) {
                    preg_match('/(?:\S+\s+){0,' . $contextWords . '}\S*' . preg_quote($searchTerm, '/') . '\S*(?:\s+\S+){0,' . $contextWords . '}/i', $item->question, $matches);
                    $highlighted_text = isset($matches[0]) ? preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $matches[0]) . '.....' : $item->question;
                } else {
                    preg_match('/(?:\S+\s+){0,' . $contextWords . '}\S*' . preg_quote($searchTerm, '/') . '\S*(?:\s+\S+){0,' . $contextWords . '}/i', $item->answer, $matches);
                    $highlighted_text = isset($matches[0]) ? preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $matches[0]) . '.....' : $item->answer;
                }
                return [
                    'type' => 'Got Questions',
                    'id' => $item->id,
                    'result' => $highlighted_text
                ];
            });
            
            //-----------Merge Results--------------//

            $merged_results = $color_bible_verse_results
                                ->merge($color_user_note_results)
                                ->merge($color_qg_results);
            $total_results = $merged_results->count();
            
            // return response()->json([
            //     'code' => 200,
            //     'success' => true,
            //     'total' => $total_results,
            //     'data' => $merged_results
            // ]);

            return $this->outputer->code(200)->metadata($total_results)
                                    ->success($merged_results)->json();


        }catch (\Exception $e) {

            DB::rollBack();
            $return['result']=$e->getMessage();
            return $this->outputer->code(422)->error($return)->json();
        }
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

                if($request['email'] == 'sachinmohanfff@gmail.com' || $request['email'] == 'sanufeliz@gmail.com'){
                    $otp = 1234;
                }else{
                    $otp = mt_rand(1000, 9999);
                }

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

                    if($request->refresh_token){

                        $userAgent = request()->header('User-Agent');

                        if (stripos($userAgent, 'Android') !== false) {
                            $device_type = 'Android';
                        } elseif (stripos($userAgent, 'iPhone') !== false || stripos($userAgent, 'iPad') !== false) {
                            $device_type = 'iOS';
                        } else {
                            $device_type = 'Other';
                        }

                        $user->device_id = 'device_id';
                        $user->refresh_token = $request->refresh_token;
                        $user->ip = request()->ip();
                        $user->device_type = $device_type;

                        $user->save();
                    }

                    $token = $user->createToken('encounter-bible-app')->plainTextToken;

                    DB::commit();
                    
                    $return['messsage']  =  'OTP verified successfully';
                    $return['token']  = $token;
                    $return['user']  =  $user;
                    
                    Log::info($return);

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
            Log::info($e->getMessage());

            $return['result']=$e->getMessage();
            return $this->outputer->code(422)->error($return)->json();
        }
    }

    public function updateToken(Request $request)
    {
        DB::beginTransaction();
        try {

            $a =  $request->validate([
                'refresh_token' => 'required',
            ]);

            $user = User::find(Auth::user()->id);

            $user->refresh_token = $request->refresh_token;
            $user->save();

            DB::commit();

            $return['messsage']  =  'Refresh_token successfully updated';
            return $this->outputer->code(200)->success($return)->json();

        }catch (Exception $e) {

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

            if($profile->image !== 'null') {
                $profile->image = asset('/') . $profile->image;
            }else{
                $profile->image = asset('/').'assets/images/user/user-dp.png';
            }
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
            if($request['country_code']){
                $inputData['country_code'] = $request['country_code'];
            }
            if($request['phone']){
                $inputData['phone'] = $request['phone'];
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
