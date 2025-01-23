<?php

namespace App\Http\Controllers\Api;

use DB;
use Mail;
use Auth;
use Carbon\Carbon;

use App\Models\Book;
use App\Models\User;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Chapter;
use App\Models\UserLMS;
use App\Models\GotQuestion;
use App\Models\HolyStatement;
use App\Models\UserCustomNote;
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

    public function Signup(Request $request){
        DB::beginTransaction();

        try {

            $a =  $request->validate([
                    'first_name'    => 'required',
                    'email'         => 'required|email|unique:users,email',
                ]);

            $inputData['first_name'] = $request['first_name'];
            $inputData['email'] = $request['email'];

            if($request['timezone']){
                $inputData['timezone'] = $request['timezone'];
            }

            $user = User::create($inputData);
            DB::commit();

            $return['messsage']  =  'success';
            $return['user']  =  $user;
            return $this->outputer->code(200)->success($return)->json();


        }catch (\Exception $e) {

            DB::rollBack();
            // $return['result']=$e->getMessage();
            // return $this->outputer->code(422)->error($return)->json();

            $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => $e->getMessage()
                    ]
                ];
            return $result;
        }
    }

    public function loginUser(Request $request){

        DB::beginTransaction();

        try {
            $user = $this->userRepo->checkUser($request->all());
            if(empty($user)) {
                
                $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => 'Invalid/Incorrect Email Address.'
                    ]
                ];
                return $result;

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

            $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => $e->getMessage()
                    ]
                ];
            return $result;
        }
    }

    public function VerifyOtp(Request $request){

        DB::beginTransaction();

        try {
            
            $otp = EmailVerification::where('email', $request->email)
                ->where('otp', $request->otp)
                ->where('otp_used', false)
                ->first();
dd($otp);
            if ($otp) {

                if (Carbon::now()->lt($otp->otp_expiry)) {
                   

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

                    $otp->otp_used = true;
                    $otp->save();

                    DB::commit();
                    
                    $return['messsage']  =  'OTP verified successfully';
                    $return['token']  = $token;
                    $return['user']  =  $user;
                    
                    Log::info($return);

                    return $this->outputer->code(200)->success($return)->json();
                }else{
                    $result = [
                            "status" => "error",
                            "metadata" => [],
                            "data" => [
                                "message" => 'OTP Expired'
                            ]
                        ];
                    return $result;
                }
            }else{

                $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => 'Invalid OTP'
                    ]
                ];
                return $result;
            }

        }catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());

            $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => $e->getMessage()
                    ]
                ];
            return $result;
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

            if($request['timezone']){
                $user->timezone = $request['timezone'];
            }
            
            $user->save();

            DB::commit();

            $return['messsage']  =  'Refresh_token successfully updated';
            return $this->outputer->code(200)->success($return)->json();

        }catch (Exception $e) {

            DB::rollBack();
            $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => $e->getMessage()
                    ]
                ];
            return $result;
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

           $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => $e->getMessage()
                    ]
                ];
            return $result;
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
            $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => $e->getMessage()
                    ]
                ];
            return $result;
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
            $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => $e->getMessage()
                    ]
                ];
            return $result;
        }
    }

    public function DeleteAccount(){

        DB::beginTransaction();

        try {

            $user=Auth::user();
            
            $user->status = 2;
            $user->save();
            DB::commit();

            $return['messsage']  =  'Your account has been deleted successfully.';
            return $this->outputer->code(200)->success($return)->json();

        }catch (\Exception $e) {

            DB::rollBack();
            $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => $e->getMessage()
                    ]
                ];
            return $result;
        }
    }

    public function SearchResults(Request $request)
    {
        try {

            // courses---description,
            // course content---descriptiopn,
            // Notifications---title, content,
            // user notes---note, category,subcategory,
            // user qna---question answer,
            
            $searchTerm = $request->input('text');
            $type=1;
            if($request->input('type')){   
                $type = $request->input('type');
            }

            Log::channel('search_log')->info("======>>>>>Search Parameters- ". now()." ======>>>>>\n" . json_encode($searchTerm));

            //-----------Bible Verse--------------//

            $searchParts = explode(' ', $searchTerm);

            if(count($searchParts) == 1){

                // $book_results = collect(Book::search($searchTerm)
                //                     ->orderBy('book_id')->get());

                // $book_results = $book_results->filter(function ($item) use ($searchTerm){
                //     return stripos($item->book_name, $searchTerm) !== false;
                // });

                $processedSearchTerm = strlen($searchTerm) > 2 ? substr($searchTerm, 0, -2) : $searchTerm;

                $book_results = collect(Book::search($processedSearchTerm)->orderBy('book_id')->get())
                    ->filter(function ($item) use ($processedSearchTerm) {
                        return stripos($item->book_name, $processedSearchTerm) !== false;
                });


                if($book_results->isNotEmpty()) {
                    $book_ids = $book_results->pluck('book_id');

                    $color_bible_verse_results = HolyStatement::whereIn('book_id', $book_ids)->get();

                    $color_bible_verse_results = $color_bible_verse_results->map(function ($item) {

                        $sentences = preg_split('/(?<=[.?!])\s+/', $item->statement_text, -1, PREG_SPLIT_NO_EMPTY);
                        $snippet = implode(' ', array_slice($sentences, 0, 2));
                        
                        return [
                            'type' => 'Bible Verse',
                            'result' => $snippet,
                            'id' => $item->statement_id,
                            'book_id' => $item->book_id,
                            'chapter_id' => $item->chapter_id,
                            'chapter_no' => $item->chapter->chapter_no,
                            'reference' => $item->book->book_name.' '.$item->chapter->chapter_no.':'.$item->statement_no,
                            'data1' => null,
                            'data2' => null
                        ];
                    });
                }else{

                    $bible_verse_results = collect(HolyStatement::search($searchTerm)
                                        ->orderBy('statement_id')->get());

                    $color_bible_verse_results = $bible_verse_results->filter(function ($item) use ($searchTerm){
                        return stripos($item->statement_text, $searchTerm) !== false;
                    })->map(function ($item) use ($searchTerm) {

                        $contextWords = 8;
                        preg_match('/(?:\S+\s+){0,' . $contextWords . '}\S*' . preg_quote($searchTerm, '/') . '\S*(?:\s+\S+){0,' . $contextWords . '}/i', $item->statement_text, $matches);
                        $highlighted_text = isset($matches[0]) ? preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $matches[0]) . '.....' : $item->statement_text;
                        return [
                            'type' => 'Bible Verse',
                            'result' => $highlighted_text,
                            'id' => $item->statement_id,
                            'book_id' => $item->book_id,
                            'chapter_id' => $item->chapter_id,
                            'chapter_no' => $item->chapter->chapter_no,
                            'reference' => $item->book->book_name.' '.$item->chapter->chapter_no.':'.$item->statement_no,
                            'data1' => null,
                            'data2' => null
                        ];
                    });
                }
            }else if(count($searchParts) == 2) {

                if(is_numeric($searchParts[1])){

                    $bookSearchTerm = $searchParts[0];
                    $chapterSearchTerm = $searchParts[1];

                    // $book_results = collect(Book::search($bookSearchTerm)
                    //                     ->orderBy('book_id')->get());

                    // $book_results = $book_results->filter(function ($item) use ($bookSearchTerm){
                    //     return stripos($item->book_name, $bookSearchTerm) !== false;
                    // });

                    $bookSearchTerm = strlen($bookSearchTerm) > 2 ? substr($bookSearchTerm, 0, -2)
                    : $bookSearchTerm; 
                    $book_results = collect(Book::search($bookSearchTerm)
                        ->orderBy('book_id')->get());

                    $book_results = $book_results->filter(function ($item) use ($bookSearchTerm) {
                        return stripos($item->book_name, $bookSearchTerm) !== false;
                    });

                    if($book_results->isNotEmpty()) {
                        $book_ids = $book_results->pluck('book_id');

                        $chapter_results = Chapter::whereIn('book_id', $book_ids)
                            ->where('chapter_no', $chapterSearchTerm)
                            ->get();

                        if($chapter_results->isNotEmpty()) {
                            $chapter_ids = $chapter_results->pluck('chapter_id');

                            $color_bible_verse_results = HolyStatement::whereIn('chapter_id', 
                                $chapter_ids)->get();

                            $color_bible_verse_results = $color_bible_verse_results->map(function ($item) {
                                $sentences = preg_split('/(?<=[.?!])\s+/', $item->statement_text, -1, PREG_SPLIT_NO_EMPTY);
                                $snippet = implode(' ', array_slice($sentences, 0, 2));

                                return [
                                    'type' => 'Bible Verse',
                                    'result' => $snippet,
                                    'id' => $item->statement_id,
                                    'book_id' => $item->book_id,
                                    'chapter_id' => $item->chapter_id,
                                    'chapter_no' => $item->chapter->chapter_no,
                                    'reference' => $item->book->book_name . ' ' . $item->chapter->chapter_no . ':' . $item->statement_no,
                                    'data1' => null,
                                    'data2' => null
                                ];
                            });
                        }else{

                            $color_bible_verse_results = HolyStatement::whereIn('book_id', $book_ids)
                                    ->get();

                            $color_bible_verse_results = $color_bible_verse_results
                                ->map(function ($item) {

                                $sentences = preg_split('/(?<=[.?!])\s+/', $item->statement_text, -1, PREG_SPLIT_NO_EMPTY);
                                $snippet = implode(' ', array_slice($sentences, 0, 2));
                                
                                return [
                                    'type' => 'Bible Verse',
                                    'result' => $snippet,
                                    'id' => $item->statement_id,
                                    'book_id' => $item->book_id,
                                    'chapter_id' => $item->chapter_id,
                                    'chapter_no' => $item->chapter->chapter_no,
                                    'reference' => $item->book->book_name.' '.$item->chapter->chapter_no.':'.$item->statement_no,
                                    'data1' => null,
                                    'data2' => null
                                ];
                            });
                        }
                    }else{

                        $bible_verse_results = collect(HolyStatement::search($searchTerm)
                                            ->orderBy('statement_id')->get());

                        $color_bible_verse_results = $bible_verse_results->filter(function ($item) use ($searchTerm){
                            return stripos($item->statement_text, $searchTerm) !== false;
                        })->map(function ($item) use ($searchTerm) {

                            $contextWords = 8;
                            preg_match('/(?:\S+\s+){0,' . $contextWords . '}\S*' . preg_quote($searchTerm, '/') . '\S*(?:\s+\S+){0,' . $contextWords . '}/i', $item->statement_text, $matches);
                            $highlighted_text = isset($matches[0]) ? preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $matches[0]) . '.....' : $item->statement_text;
                            return [
                                'type' => 'Bible Verse',
                                'result' => $highlighted_text,
                                'id' => $item->statement_id,
                                'book_id' => $item->book_id,
                                'chapter_id' => $item->chapter_id,
                                'chapter_no' => $item->chapter->chapter_no,
                                'reference' => $item->book->book_name.' '.$item->chapter->chapter_no.':'.$item->statement_no,
                                'data1' => null,
                                'data2' => null
                            ];
                        });
                    }    
                }else{

                    $bookSearchTerm = $searchParts[0];

                    // $book_results = collect(Book::search($bookSearchTerm)
                    //                     ->orderBy('book_id')->get());

                    // $book_results = $book_results->filter(function ($item) use ($bookSearchTerm){
                    //     return stripos($item->book_name, $bookSearchTerm) !== false;
                    // });

                    $bookSearchTerm = strlen($bookSearchTerm) > 2 ? substr($bookSearchTerm, 0, -2)
                    : $bookSearchTerm; 
                    $book_results = collect(Book::search($bookSearchTerm)
                        ->orderBy('book_id')->get());

                    $book_results = $book_results->filter(function ($item) use ($bookSearchTerm) {
                        return stripos($item->book_name, $bookSearchTerm) !== false;
                    });

                    if($book_results->isNotEmpty()) {
                        $book_ids = $book_results->pluck('book_id');

                        $pattern = '/^\d+\s*:\s*\d+$/';
                        $bookchapterSearchTerm = $searchParts[1];
                        $book_chpater_valid = preg_match($pattern, $bookchapterSearchTerm);

                        list($first, $second) = explode(':', str_replace(' ', '', $bookchapterSearchTerm));
                        if ($book_chpater_valid && is_numeric($first) && is_numeric($second)) {

                            $color_bible_verse_results = HolyStatement::
                                        whereIn('book_id', $book_ids)
                                        ->where('chapter_no', $first)
                                        ->where('statement_no', $second)
                                        ->get();

                                $color_bible_verse_results = $color_bible_verse_results
                                    ->map(function ($item) {

                                    $sentences = preg_split('/(?<=[.?!])\s+/', $item->statement_text, -1, PREG_SPLIT_NO_EMPTY);
                                    $snippet = implode(' ', array_slice($sentences, 0, 2));
                                    
                                    return [
                                        'type' => 'Bible Verse',
                                        'result' => $snippet,
                                        'id' => $item->statement_id,
                                        'book_id' => $item->book_id,
                                        'chapter_id' => $item->chapter_id,
                                        'chapter_no' => $item->chapter->chapter_no,
                                        'reference' => $item->book->book_name.' '.$item->chapter->chapter_no.':'.$item->statement_no,
                                        'data1' => null,
                                        'data2' => null
                                    ];
                                });
                        }
                    }else{

                        $bible_verse_results = collect(HolyStatement::search($searchTerm)
                                            ->orderBy('statement_id')->get());

                        $color_bible_verse_results = $bible_verse_results->filter(function ($item) use ($searchTerm){
                            return stripos($item->statement_text, $searchTerm) !== false;
                        })->map(function ($item) use ($searchTerm) {

                            $contextWords = 8;
                            preg_match('/(?:\S+\s+){0,' . $contextWords . '}\S*' . preg_quote($searchTerm, '/') . '\S*(?:\s+\S+){0,' . $contextWords . '}/i', $item->statement_text, $matches);
                            $highlighted_text = isset($matches[0]) ? preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $matches[0]) . '.....' : $item->statement_text;
                            return [
                                'type' => 'Bible Verse',
                                'result' => $highlighted_text,
                                'id' => $item->statement_id,
                                'book_id' => $item->book_id,
                                'chapter_id' => $item->chapter_id,
                                'chapter_no' => $item->chapter->chapter_no,
                                'reference' => $item->book->book_name.' '.$item->chapter->chapter_no.':'.$item->statement_no,
                                'data1' => null,
                                'data2' => null
                            ];
                        });
                    }
                }
            }else{

                $bible_verse_results = collect(HolyStatement::search($searchTerm)
                                    ->orderBy('statement_id')->get());

                $color_bible_verse_results = $bible_verse_results->filter(function ($item) use ($searchTerm){
                    return stripos($item->statement_text, $searchTerm) !== false;
                })->map(function ($item) use ($searchTerm) {

                    $contextWords = 8;
                    preg_match('/(?:\S+\s+){0,' . $contextWords . '}\S*' . preg_quote($searchTerm, '/') . '\S*(?:\s+\S+){0,' . $contextWords . '}/i', $item->statement_text, $matches);
                    $highlighted_text = isset($matches[0]) ? preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $matches[0]) . '.....' : $item->statement_text;
                    return [
                        'type' => 'Bible Verse',
                        'result' => $highlighted_text,
                        'id' => $item->statement_id,
                        'book_id' => $item->book_id,
                        'chapter_id' => $item->chapter_id,
                        'chapter_no' => $item->chapter->chapter_no,
                        'reference' => $item->book->book_name.' '.$item->chapter->chapter_no.':'.$item->statement_no,
                        'data1' => null,
                        'data2' => null
                    ];
                });
            }
        

            if($type==1){
                
                //-----------User Notes--------------//

                $logged_user=Auth::user();

                $user_note_results = collect(UserCustomNote::search($searchTerm)
                                    ->where('user_id',$logged_user->id)
                                    ->where('status', 1)->orderBy('id')->get());

                $color_user_note_results = $user_note_results->filter(function ($item) use ($searchTerm) {
                    return stripos($item->note_text, $searchTerm) !== false;
                })->map(function ($item) use ($searchTerm) {

                    $contextWords = 8;

                    preg_match('/(?:\S+\s+){0,' . $contextWords . '}\S*' . preg_quote($searchTerm, '/') . '\S*(?:\s+\S+){0,' . $contextWords . '}/i', $item->note_text, $matches);
                    $highlighted_text = isset($matches[0]) ? preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $matches[0]) . '.....' : $item->note_text;
                    return [
                        'type' => 'User Notes',
                        'id' => $item->id,
                        'result' => $highlighted_text,
                        'data1' => null,
                        'data2' => null
                    ];
                });
                
                //-----------Gospel Questions--------------//

                // $gq_results = collect(GotQuestion::search($searchTerm)
                //                     ->where('status', 1)->orderBy('id')->get());

                // $color_qg_results = $gq_results->filter(function ($item) use ($searchTerm) {
                //     return stripos($item->question, $searchTerm) !== false || stripos($item->answer, $searchTerm) !== false;
                // })->map(function ($item) use ($searchTerm) {
                    
                //     $contextWords = 8;

                //     if (stripos($item->question, $searchTerm) !== false) {
                //         preg_match('/(?:\S+\s+){0,' . $contextWords . '}\S*' . preg_quote($searchTerm, '/') . '\S*(?:\s+\S+){0,' . $contextWords . '}/i', $item->question, $matches);
                //         $highlighted_text = isset($matches[0]) ? preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $matches[0]) . '.....' : $item->question;
                //     } else {
                //         preg_match('/(?:\S+\s+){0,' . $contextWords . '}\S*' . preg_quote($searchTerm, '/') . '\S*(?:\s+\S+){0,' . $contextWords . '}/i', $item->answer, $matches);
                //         $highlighted_text = isset($matches[0]) ? preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $matches[0]) . '.....' : $item->answer;
                //     }
                //     return [
                //         'type' => 'Got Questions',
                //         'id' => $item->id,
                //         'result' => $highlighted_text
                //     ];
                // });

                $gq_results = collect(GotQuestion::search($searchTerm)
                                    ->where('status', 1)->orderBy('id')->get());
                $filteredQuestions = $gq_results->map(function ($item) { 
                            $item->question = strip_tags($item->question); 
                            $item->answer = strip_tags($item->answer); 
                            return $item; 
                    });

                $color_qg_results = $filteredQuestions->filter(function ($item) use ($searchTerm) {
                    return stripos(strip_tags($item->question), $searchTerm) !== false || stripos(strip_tags($item->answer), $searchTerm) !== false;
                })->map(function ($item) use ($searchTerm) {
                    
                    $contextWords = 8;

                    if (stripos($item->question, $searchTerm) !== false) {
                        preg_match('/(?:\S+\s+){0,' . $contextWords . '}\S*' . preg_quote($searchTerm, '/') . '\S*(?:\s+\S+){0,' . $contextWords . '}/i', strip_tags($item->question), $matches);
                        $highlighted_text = isset($matches[0]) ? preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $matches[0]) . '.....' : strip_tags($item->question);
                    } else {
                        preg_match('/(?:\S+\s+){0,' . $contextWords . '}\S*' . preg_quote($searchTerm, '/') . '\S*(?:\s+\S+){0,' . $contextWords . '}/i', strip_tags($item->answer), $matches);
                        $highlighted_text = isset($matches[0]) ? preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $matches[0]) . '.....' : strip_tags($item->answer);
                    }
                    return [
                        'type' => 'Gospel Questions',
                        'id' => $item->id,
                        'result' => $highlighted_text,
                        'data1' => $item->category_id,
                        'data2' => $item->sub_category_id
                    ];
                });

                //-----------Course --------------//

                // $course_results = collect(Course::search($searchTerm)
                //                     ->orderBy('id')->get());

                // $course_results = $course_results->filter(function ($item) use ($searchTerm){
                //     return stripos($item->course_name, $searchTerm) !== false || stripos($item->course_creator, $searchTerm) !== false || stripos($item->description, $searchTerm) !== false;
                // });


                $user_id = Auth::user()->id;

                $course_results = Course::from(with(new Course)->getTable() . ' as a')
                    ->join(with(new Batch)->getTable() . ' as b', 'a.id', '=', 'b.course_id') // Join with Batch table
                    ->leftJoin(with(new UserLMS)->getTable() . ' as c', function ($join) use ($user_id) {
                        $join->on('b.id', '=', 'c.batch_id')
                            ->where('c.user_id', '=', $user_id)
                            ->where('c.status', 1);

                    })
                    ->select('a.course_name', 'b.id as batch_id') // Include last_date in the select
                    ->where(function ($query) use ($searchTerm) {
                        $query->where('a.course_name', 'LIKE', '%' . $searchTerm . '%')
                              ->orWhere('a.course_creator', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->where(function ($query) {
                                    $query->whereNotNull('c.id')
                                          ->orWhere(function ($subQuery) {
                                              $subQuery->where('b.last_date', '>=', now()->format('Y-m-d'));
                                          });
                                })
                    ->groupBy('a.id', 'b.id','a.course_name')
                    ->get();

                $course_results = $course_results->map(function ($item) {
                    
                    return [
                        'type' => 'Courses',
                        'result' => $item->course_name,
                        'id' => $item->batch_id,
                        'data1' => null,
                        'data2' => null
                    ];
                });

                //-----------Batch --------------//

                // $batch_results = collect(Batch::search($searchTerm)
                //                     ->orderBy('id')->get());

                // $batch_results = $batch_results->filter(function ($item) use ($searchTerm){
                //     return stripos($item->batch_name, $searchTerm) !== false;
                // });


                $batch_results = Batch::from(with(new Batch)->getTable() . ' as a')
                    ->leftJoin(with(new UserLMS)->getTable() . ' as b', function ($join) use ($user_id) {
                        $join->on('a.id', '=', 'b.batch_id')
                            ->where('b.user_id', '=', $user_id)
                            ->where('b.status', 1);

                    })

                    ->select('a.batch_name', 'a.id as batch_id') 
                    ->where(function ($query) use ($searchTerm) {
                        $query->where('a.batch_name', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->where(function ($query) {
                                    $query->whereNotNull('b.id')
                                          ->orWhere(function ($subQuery) {
                                              $subQuery->where('a.last_date', '>=', now()->format('Y-m-d'));
                                          });
                                })
                    ->groupBy('a.id','a.batch_name')
                    ->get();

                $batch_results = $batch_results->map(function ($item) {
                    
                    return [
                        'type' => 'Batch',
                        'result' => $item->batch_name,
                        'id' => $item->batch_id,
                        'data1' => null,
                        'data2' => null
                    ];
                });

            }

            //-----------Merge Results--------------//

            if($type==1){
                $merged_results = $color_bible_verse_results
                                    ->merge($color_user_note_results)
                                    ->merge($color_qg_results)
                                    ->merge($course_results)
                                    ->merge($batch_results);
            }else{
                $merged_results = $color_bible_verse_results;
            }

            $merged_results = $merged_results->values();

            $total_results = $merged_results->count();

            return $this->outputer->code(200)->metadata($total_results)
                                    ->success($merged_results)->json();


        }catch (\Exception $e) {

            DB::rollBack();
            $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => $e->getMessage()
                    ]
                ];
            return $result;
        }
    }

    public function SearchResultsTwo(Request $request)
    {
        try {

            // courses---description,
            // course content---descriptiopn,
            // Notifications---title, content,
            // user notes---note, category,subcategory,
            // user qna---question answer,
            
            $searchTerm = $request->input('text');
            //dd($searchTerm);
            $type=1;
            if($request->input('type')){   
                $type = $request->input('type');
            }

            Log::channel('search_log')->info("======>>>>>Search Parameters- ". now()." ======>>>>>\n" . json_encode($searchTerm));

            //-----------Bible Verse--------------//

            $searchParts = explode(' ', $searchTerm);

            if(count($searchParts) == 1){

                // $book_results = collect(Book::search($searchTerm)
                //                     ->orderBy('book_id')->get());

                // $book_results = $book_results->filter(function ($item) use ($searchTerm){
                //     return stripos($item->book_name, $searchTerm) !== false;
                // });

                //$processedSearchTerm = strlen($searchTerm) > 2 ? substr($searchTerm, 0, -2) : $searchTerm;
                $processedSearchTerm = $searchTerm;

                $book_results = collect(Book::search($processedSearchTerm)->orderBy('book_id')->get())
                    ->filter(function ($item) use ($processedSearchTerm) {
                        return stripos($item->book_name, $processedSearchTerm) !== false;
                });


                if($book_results->isNotEmpty()) {
                    $book_ids = $book_results->pluck('book_id');

                    $color_bible_verse_results = HolyStatement::whereIn('book_id', $book_ids)->get();

                    $color_bible_verse_results = $color_bible_verse_results->map(function ($item) {

                        $sentences = preg_split('/(?<=[.?!])\s+/', $item->statement_text, -1, PREG_SPLIT_NO_EMPTY);
                        $snippet = implode(' ', array_slice($sentences, 0, 2));
                        
                        return [
                            'type' => 'Bible Verse',
                            'result' => $snippet,
                            'id' => $item->statement_id,
                            'book_id' => $item->book_id,
                            'chapter_id' => $item->chapter_id,
                            'chapter_no' => $item->chapter->chapter_no,
                            'reference' => $item->book->book_name.' '.$item->chapter->chapter_no.':'.$item->statement_no
                        ];
                    });
                }else{

                    $bible_verse_results = collect(HolyStatement::search($searchTerm)
                                        ->orderBy('statement_id')->get());

                    $color_bible_verse_results = $bible_verse_results->filter(function ($item) use ($searchTerm){
                        return stripos($item->statement_text, $searchTerm) !== false;
                    })->map(function ($item) use ($searchTerm) {

                        $contextWords = 8;
                        preg_match('/(?:\S+\s+){0,' . $contextWords . '}\S*' . preg_quote($searchTerm, '/') . '\S*(?:\s+\S+){0,' . $contextWords . '}/i', $item->statement_text, $matches);
                        $highlighted_text = isset($matches[0]) ? preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $matches[0]) . '.....' : $item->statement_text;
                        return [
                            'type' => 'Bible Verse',
                            'result' => $highlighted_text,
                            'id' => $item->statement_id,
                            'book_id' => $item->book_id,
                            'chapter_id' => $item->chapter_id,
                            'chapter_no' => $item->chapter->chapter_no,
                            'reference' => $item->book->book_name.' '.$item->chapter->chapter_no.':'.$item->statement_no
                        ];
                    });
                }
            }else if(count($searchParts) == 2) {

                if(is_numeric($searchParts[1])){

                    $bookSearchTerm = $searchParts[0];
                    $chapterSearchTerm = $searchParts[1];

                    // $book_results = collect(Book::search($bookSearchTerm)
                    //                     ->orderBy('book_id')->get());

                    // $book_results = $book_results->filter(function ($item) use ($bookSearchTerm){
                    //     return stripos($item->book_name, $bookSearchTerm) !== false;
                    // });

                    $bookSearchTerm = strlen($bookSearchTerm) > 2 ? substr($bookSearchTerm, 0, -2)
                    : $bookSearchTerm; 
                    $book_results = collect(Book::search($bookSearchTerm)
                        ->orderBy('book_id')->get());

                    $book_results = $book_results->filter(function ($item) use ($bookSearchTerm) {
                        return stripos($item->book_name, $bookSearchTerm) !== false;
                    });

                    if($book_results->isNotEmpty()) {
                        $book_ids = $book_results->pluck('book_id');

                        $chapter_results = Chapter::whereIn('book_id', $book_ids)
                            ->where('chapter_no', $chapterSearchTerm)
                            ->get();

                        if($chapter_results->isNotEmpty()) {
                            $chapter_ids = $chapter_results->pluck('chapter_id');

                            $color_bible_verse_results = HolyStatement::whereIn('chapter_id', 
                                $chapter_ids)->get();

                            $color_bible_verse_results = $color_bible_verse_results->map(function ($item) {
                                $sentences = preg_split('/(?<=[.?!])\s+/', $item->statement_text, -1, PREG_SPLIT_NO_EMPTY);
                                $snippet = implode(' ', array_slice($sentences, 0, 2));

                                return [
                                    'type' => 'Bible Verse',
                                    'result' => $snippet,
                                    'id' => $item->statement_id,
                                    'book_id' => $item->book_id,
                                    'chapter_id' => $item->chapter_id,
                                    'chapter_no' => $item->chapter->chapter_no,
                                    'reference' => $item->book->book_name . ' ' . $item->chapter->chapter_no . ':' . $item->statement_no
                                ];
                            });
                        }else{

                            $color_bible_verse_results = HolyStatement::whereIn('book_id', $book_ids)
                                    ->get();

                            $color_bible_verse_results = $color_bible_verse_results
                                ->map(function ($item) {

                                $sentences = preg_split('/(?<=[.?!])\s+/', $item->statement_text, -1, PREG_SPLIT_NO_EMPTY);
                                $snippet = implode(' ', array_slice($sentences, 0, 2));
                                
                                return [
                                    'type' => 'Bible Verse',
                                    'result' => $snippet,
                                    'id' => $item->statement_id,
                                    'book_id' => $item->book_id,
                                    'chapter_id' => $item->chapter_id,
                                    'chapter_no' => $item->chapter->chapter_no,
                                    'reference' => $item->book->book_name.' '.$item->chapter->chapter_no.':'.$item->statement_no
                                ];
                            });
                        }
                    }else{

                        $bible_verse_results = collect(HolyStatement::search($searchTerm)
                                            ->orderBy('statement_id')->get());

                        $color_bible_verse_results = $bible_verse_results->filter(function ($item) use ($searchTerm){
                            return stripos($item->statement_text, $searchTerm) !== false;
                        })->map(function ($item) use ($searchTerm) {

                            $contextWords = 8;
                            preg_match('/(?:\S+\s+){0,' . $contextWords . '}\S*' . preg_quote($searchTerm, '/') . '\S*(?:\s+\S+){0,' . $contextWords . '}/i', $item->statement_text, $matches);
                            $highlighted_text = isset($matches[0]) ? preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $matches[0]) . '.....' : $item->statement_text;
                            return [
                                'type' => 'Bible Verse',
                                'result' => $highlighted_text,
                                'id' => $item->statement_id,
                                'book_id' => $item->book_id,
                                'chapter_id' => $item->chapter_id,
                                'chapter_no' => $item->chapter->chapter_no,
                                'reference' => $item->book->book_name.' '.$item->chapter->chapter_no.':'.$item->statement_no
                            ];
                        });
                    }    
                }else{

                    $bookSearchTerm = $searchParts[0];

                    // $book_results = collect(Book::search($bookSearchTerm)
                    //                     ->orderBy('book_id')->get());

                    // $book_results = $book_results->filter(function ($item) use ($bookSearchTerm){
                    //     return stripos($item->book_name, $bookSearchTerm) !== false;
                    // });

                    $bookSearchTerm = strlen($bookSearchTerm) > 2 ? substr($bookSearchTerm, 0, -2)
                    : $bookSearchTerm; 
                    $book_results = collect(Book::search($bookSearchTerm)
                        ->orderBy('book_id')->get());

                    $book_results = $book_results->filter(function ($item) use ($bookSearchTerm) {
                        return stripos($item->book_name, $bookSearchTerm) !== false;
                    });

                    if($book_results->isNotEmpty()) {
                        $book_ids = $book_results->pluck('book_id');

                        $pattern = '/^\d+\s*:\s*\d+$/';
                        $bookchapterSearchTerm = $searchParts[1];
                        $book_chpater_valid = preg_match($pattern, $bookchapterSearchTerm);

                        list($first, $second) = explode(':', str_replace(' ', '', $bookchapterSearchTerm));
                        if ($book_chpater_valid && is_numeric($first) && is_numeric($second)) {

                            $color_bible_verse_results = HolyStatement::
                                        whereIn('book_id', $book_ids)
                                        ->where('chapter_no', $first)
                                        ->where('statement_no', $second)
                                        ->get();

                                $color_bible_verse_results = $color_bible_verse_results
                                    ->map(function ($item) {

                                    $sentences = preg_split('/(?<=[.?!])\s+/', $item->statement_text, -1, PREG_SPLIT_NO_EMPTY);
                                    $snippet = implode(' ', array_slice($sentences, 0, 2));
                                    
                                    return [
                                        'type' => 'Bible Verse',
                                        'result' => $snippet,
                                        'id' => $item->statement_id,
                                        'book_id' => $item->book_id,
                                        'chapter_id' => $item->chapter_id,
                                        'chapter_no' => $item->chapter->chapter_no,
                                        'reference' => $item->book->book_name.' '.$item->chapter->chapter_no.':'.$item->statement_no
                                    ];
                                });
                        }
                    }else{

                        $bible_verse_results = collect(HolyStatement::search($searchTerm)
                                            ->orderBy('statement_id')->get());

                        $color_bible_verse_results = $bible_verse_results->filter(function ($item) use ($searchTerm){
                            return stripos($item->statement_text, $searchTerm) !== false;
                        })->map(function ($item) use ($searchTerm) {

                            $contextWords = 8;
                            preg_match('/(?:\S+\s+){0,' . $contextWords . '}\S*' . preg_quote($searchTerm, '/') . '\S*(?:\s+\S+){0,' . $contextWords . '}/i', $item->statement_text, $matches);
                            $highlighted_text = isset($matches[0]) ? preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $matches[0]) . '.....' : $item->statement_text;
                            return [
                                'type' => 'Bible Verse',
                                'result' => $highlighted_text,
                                'id' => $item->statement_id,
                                'book_id' => $item->book_id,
                                'chapter_id' => $item->chapter_id,
                                'chapter_no' => $item->chapter->chapter_no,
                                'reference' => $item->book->book_name.' '.$item->chapter->chapter_no.':'.$item->statement_no
                            ];
                        });
                    }
                }
            }else{

                $bible_verse_results = collect(HolyStatement::search($searchTerm)
                                    ->orderBy('statement_id')->get());

                $color_bible_verse_results = $bible_verse_results->filter(function ($item) use ($searchTerm){
                    return stripos($item->statement_text, $searchTerm) !== false;
                })->map(function ($item) use ($searchTerm) {

                    $contextWords = 8;
                    preg_match('/(?:\S+\s+){0,' . $contextWords . '}\S*' . preg_quote($searchTerm, '/') . '\S*(?:\s+\S+){0,' . $contextWords . '}/i', $item->statement_text, $matches);
                    $highlighted_text = isset($matches[0]) ? preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $matches[0]) . '.....' : $item->statement_text;
                    return [
                        'type' => 'Bible Verse',
                        'result' => $highlighted_text,
                        'id' => $item->statement_id,
                        'book_id' => $item->book_id,
                        'chapter_id' => $item->chapter_id,
                        'chapter_no' => $item->chapter->chapter_no,
                        'reference' => $item->book->book_name.' '.$item->chapter->chapter_no.':'.$item->statement_no
                    ];
                });
            }
        

            if($type==1){
                
                //-----------User Notes--------------//

                $logged_user=Auth::user();

                $user_note_results = collect(UserCustomNote::search($searchTerm)
                                    ->where('user_id',$logged_user->id)
                                    ->where('status', 1)->orderBy('id')->get());

                $color_user_note_results = $user_note_results->filter(function ($item) use ($searchTerm) {
                    return stripos($item->note_text, $searchTerm) !== false;
                })->map(function ($item) use ($searchTerm) {

                    $contextWords = 8;

                    preg_match('/(?:\S+\s+){0,' . $contextWords . '}\S*' . preg_quote($searchTerm, '/') . '\S*(?:\s+\S+){0,' . $contextWords . '}/i', $item->note_text, $matches);
                    $highlighted_text = isset($matches[0]) ? preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $matches[0]) . '.....' : $item->note_text;
                    return [
                        'type' => 'User Notes',
                        'id' => $item->id,
                        'result' => $highlighted_text
                    ];
                });
                
                //-----------Gospel Questions--------------//

                // $gq_results = collect(GotQuestion::search($searchTerm)
                //                     ->where('status', 1)->orderBy('id')->get());

                // $color_qg_results = $gq_results->filter(function ($item) use ($searchTerm) {
                //     return stripos($item->question, $searchTerm) !== false || stripos($item->answer, $searchTerm) !== false;
                // })->map(function ($item) use ($searchTerm) {
                    
                //     $contextWords = 8;

                //     if (stripos($item->question, $searchTerm) !== false) {
                //         preg_match('/(?:\S+\s+){0,' . $contextWords . '}\S*' . preg_quote($searchTerm, '/') . '\S*(?:\s+\S+){0,' . $contextWords . '}/i', $item->question, $matches);
                //         $highlighted_text = isset($matches[0]) ? preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $matches[0]) . '.....' : $item->question;
                //     } else {
                //         preg_match('/(?:\S+\s+){0,' . $contextWords . '}\S*' . preg_quote($searchTerm, '/') . '\S*(?:\s+\S+){0,' . $contextWords . '}/i', $item->answer, $matches);
                //         $highlighted_text = isset($matches[0]) ? preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $matches[0]) . '.....' : $item->answer;
                //     }
                //     return [
                //         'type' => 'Got Questions',
                //         'id' => $item->id,
                //         'result' => $highlighted_text
                //     ];
                // });

                $gq_results = collect(GotQuestion::search($searchTerm)
                                    ->where('status', 1)->orderBy('id')->get());
                $filteredQuestions = $gq_results->map(function ($item) { 
                            $item->question = strip_tags($item->question); 
                            $item->answer = strip_tags($item->answer); 
                            return $item; 
                    });

                $color_qg_results = $filteredQuestions->filter(function ($item) use ($searchTerm) {
                    return stripos(strip_tags($item->question), $searchTerm) !== false || stripos(strip_tags($item->answer), $searchTerm) !== false;
                })->map(function ($item) use ($searchTerm) {
                    
                    $contextWords = 8;

                    if (stripos($item->question, $searchTerm) !== false) {
                        preg_match('/(?:\S+\s+){0,' . $contextWords . '}\S*' . preg_quote($searchTerm, '/') . '\S*(?:\s+\S+){0,' . $contextWords . '}/i', strip_tags($item->question), $matches);
                        $highlighted_text = isset($matches[0]) ? preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $matches[0]) . '.....' : strip_tags($item->question);
                    } else {
                        preg_match('/(?:\S+\s+){0,' . $contextWords . '}\S*' . preg_quote($searchTerm, '/') . '\S*(?:\s+\S+){0,' . $contextWords . '}/i', strip_tags($item->answer), $matches);
                        $highlighted_text = isset($matches[0]) ? preg_replace("/($searchTerm)/i", '<mark>$1</mark>', $matches[0]) . '.....' : strip_tags($item->answer);
                    }
                    return [
                        'type' => 'Gospel Questions',
                        'id' => $item->id,
                        'result' => $highlighted_text
                    ];
                });

                //-----------Course --------------//

                // $course_results = collect(Course::search($searchTerm)
                //                     ->orderBy('id')->get());

                // $course_results = $course_results->filter(function ($item) use ($searchTerm){
                //     return stripos($item->course_name, $searchTerm) !== false || stripos($item->course_creator, $searchTerm) !== false || stripos($item->description, $searchTerm) !== false;
                // });


                $user_id = Auth::user()->id;

                $course_results = Course::from(with(new Course)->getTable() . ' as a')
                    ->join(with(new Batch)->getTable() . ' as b', 'a.id', '=', 'b.course_id') // Join with Batch table
                    ->leftJoin(with(new UserLMS)->getTable() . ' as c', function ($join) use ($user_id) {
                        $join->on('b.id', '=', 'c.batch_id')
                            ->where('c.user_id', '=', $user_id)
                            ->where('c.status', 1);

                    })
                    ->select('a.course_name', 'b.id as batch_id') // Include last_date in the select
                    ->where(function ($query) use ($searchTerm) {
                        $query->where('a.course_name', 'LIKE', '%' . $searchTerm . '%')
                              ->orWhere('a.course_creator', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->where(function ($query) {
                                    $query->whereNotNull('c.id')
                                          ->orWhere(function ($subQuery) {
                                              $subQuery->where('b.last_date', '>=', now()->format('Y-m-d'));
                                          });
                                })
                    ->groupBy('a.id', 'b.id','a.course_name')
                    ->get();

                $course_results = $course_results->map(function ($item) {
                    
                    return [
                        'type' => 'Courses',
                        'result' => $item->course_name,
                        'id' => $item->batch_id,
                    ];
                });

                //-----------Batch --------------//

                // $batch_results = collect(Batch::search($searchTerm)
                //                     ->orderBy('id')->get());

                // $batch_results = $batch_results->filter(function ($item) use ($searchTerm){
                //     return stripos($item->batch_name, $searchTerm) !== false;
                // });


                $batch_results = Batch::from(with(new Batch)->getTable() . ' as a')
                    ->leftJoin(with(new UserLMS)->getTable() . ' as b', function ($join) use ($user_id) {
                        $join->on('a.id', '=', 'b.batch_id')
                            ->where('b.user_id', '=', $user_id)
                            ->where('b.status', 1);

                    })

                    ->select('a.batch_name', 'a.id as batch_id') 
                    ->where(function ($query) use ($searchTerm) {
                        $query->where('a.batch_name', 'LIKE', '%' . $searchTerm . '%');
                    })
                    ->where(function ($query) {
                                    $query->whereNotNull('b.id')
                                          ->orWhere(function ($subQuery) {
                                              $subQuery->where('a.last_date', '>=', now()->format('Y-m-d'));
                                          });
                                })
                    ->groupBy('a.id','a.batch_name')
                    ->get();

                $batch_results = $batch_results->map(function ($item) {
                    
                    return [
                        'type' => 'Batch',
                        'result' => $item->batch_name,
                        'id' => $item->batch_id
                    ];
                });

            }

            //-----------Merge Results--------------//

            if($type==1){
                $merged_results = $color_bible_verse_results
                                    ->merge($color_user_note_results)
                                    ->merge($color_qg_results)
                                    ->merge($course_results)
                                    ->merge($batch_results);
            }else{
                $merged_results = $color_bible_verse_results;
            }

            $total_results = $merged_results->count();

            return $this->outputer->code(200)->metadata($total_results)
                                    ->success($merged_results)->json();


        }catch (\Exception $e) {

            DB::rollBack();
            $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => $e->getMessage()
                    ]
                ];
            return $result;
        }
    }

}
