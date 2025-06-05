<?php

namespace App\Http\Controllers\Api;

use DB;
use Auth;
use Carbon\Carbon;
use App\Helpers\Outputer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;


use App\Models\User;
use App\Models\Book;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Chapter;
use App\Models\UserLMS;
use App\Models\Testament;
use App\Models\AppBanner;
use App\Models\BookImage;
use App\Models\BibleChange;
use App\Models\Notification;
use App\Models\CourseContent;
use App\Models\HolyStatement;
use App\Models\CourseDayVerse;
use App\Models\BibleVerseImage;
use App\Models\DailyBibleVerse;
use App\Models\UserDailyReading;

use App\Http\Controllers\Controller;
class HomeController extends Controller
{
    public function __construct(Outputer $outputer){

        $this->outputer = $outputer;
    }

    public function Home(Request $request){
        
        try {

            if(auth('sanctum')->check()) {
               
            /*--------Authenticated User---------*/

                $user_id = auth('sanctum')->id();

                $login_user = User::select('id','image','timezone')->where('id',$user_id)->first();

                $userTimezone = $login_user->timezone ?? 'UTC';
                if($login_user->image !== null) {
                    $login_user->image = asset('/') . $login_user->image;
                }else{
                    $login_user->image = asset('/').'assets/images/user/user-dp.png';
                }

                $today_string = Carbon::now($userTimezone)->format('Y-m-d');

            }else{

                $userTimezone = 'UTC';

                $login_user = (object) [
                    'id' => Null,
                    'image' => asset('assets/images/user/user-dp.png'),
                    'timezone' =>$userTimezone,
                    'user_name' => 'Guest User'
                ];

                $today_string = Carbon::now($userTimezone)->format('Y-m-d');
            }

            /*---------Daily Bible verse----------*/

            $cacheKey = 'daily_bible_verse_' . $today_string;

            $bible_verse = Cache::remember($cacheKey, Carbon::now($userTimezone)->endOfDay(), 
                function () use ($today_string) {
                    return DailyBibleVerse::select('id', 'verse_id')
                        ->whereDate('date', $today_string)
                        ->where('status', 1)
                        ->first() ?? DailyBibleVerse::where('status', 1)->inRandomOrder()->first();
                });

            $bgImage = BibleVerseImage::where('status', 2)->first();
            if (!$bgImage) {
                $bgImage = BibleVerseImage::where('status', 1)->inRandomOrder()->first();

                if (!$bgImage) {
                    $path = 'assets/images/defualt_bg.jpg';
                }else{
                    $path = $bgImage->path;
                }
            }else{
                $path = $bgImage->path;
            }

            if($bible_verse) {
                $statement = HolyStatement::where('statement_id',$bible_verse->verse_id)->first();
                $bible_verse->data1 = $statement->statement_text;
                $bible_verse->data2 = $statement->book->book_name.'  '.$statement->chapter->chapter_no.' : '.$statement->statement_no;
                $bible_verse->data3 = asset('/') . $path;
                
                $bible_verse->makeHidden(['bible_name','testament_name','book_name','chapter_name','verse_no','theme_name']);
            }

            /*---------Home Banner----------*/

            $banners = AppBanner::select('title','link','path','status')
                            ->where('status', 2)
                            ->get();
            $banners->transform(function ($item){

                if ($item->path !== null) {
                    $item->path = asset('/') . $item->path;
                } else {
                    $item->path = null;
                }

                return $item;
            });

            /*---------Courses----------*/

            $courses = Course::from(with(new Course)->getTable() . ' as a')
                ->join(with(new Batch)->getTable() . ' as b', 'a.id', 'b.course_id')
                ->join(with(new CourseContent)->getTable() . ' as cc', 'a.id', '=', 'cc.course_id')
                ->join(with(new CourseDayVerse)->getTable() . ' as cdv', 'cc.id', '=', 'cdv.course_content_id');

            if (auth('sanctum')->check()) {
                
                $courses->leftJoin('user_l_m_s as ul', function ($join) use ($login_user) {
                    $join->on('b.id', '=', 'ul.batch_id')
                        ->where('ul.user_id', '=', $login_user->id)
                        ->where('ul.status', 1);
                });
            }
            $courses->select(
                'a.id',
                'a.course_name as data1',
                'a.course_creator as data2',
                'a.thumbnail as image',
                'b.id as batch_id',
                'b.batch_name as data3',
                'b.start_date',
                'b.end_date',
                'b.last_date',
                'a.no_of_days'
            )
            ->where(function ($query) {
                if (auth('sanctum')->check()) {
                    $query->where('b.last_date', '>=', now()->format('Y-m-d'));
                    $query->orWhereNotNull('ul.id');
                }else{
                    $query->where('b.end_date', '>=', now()->format('Y-m-d'));
                }
            })
            ->where('a.status', 1)
            ->where('b.status', 1)
            ->where('cc.status', 1)
            ->where('cdv.status', 1)
            ->groupBy('a.id', 'a.course_name', 'a.course_creator', 'a.thumbnail', 'b.id', 'b.batch_name', 'b.start_date', 'a.no_of_days');

            if ($request['search_word']) {
                $courses->where(function ($query) use ($request) {
                    $query->where('a.course_name', 'like', $request['search_word'] . '%')
                        ->orWhere('a.course_creator', 'like', $request['search_word'] . '%');
                });
            }

            if ($request['length']) {
                $courses->take($request['length']);
            }

            $courses = $courses->orderby('id')->get();

            $courses->transform(function ($item) use ($login_user) {
                $item->data4 = 'Enrol Now';
                $item->data5 = '0 %';
                $orderWeight = 2;
                $can_enroll = true;

                if ($item->start_date > now()->format('Y-m-d')) {
                    $item->data4 = 'Upcoming';
                    $orderWeight = 3;

                } elseif (auth('sanctum')->check()) {
                    $userLms = UserLMS::where('user_id', $login_user->id)
                        ->where('course_id', $item->id)
                        ->where('batch_id', $item->batch_id)
                        ->first();

                    if ($userLms) {
                        $readingsCount = UserDailyReading::where('user_lms_id', $userLms->id)->count();
                        $percentage = round(($readingsCount / $item->no_of_days) * 100, 2);

                        if ($readingsCount > 0) {
                            if ($readingsCount < $item->no_of_days) {
                                $item->data4 = 'Ongoing';
                                $orderWeight = 1;
                                $can_enroll = false;
                            } else {
                                $item->data4 = 'Completed';
                                $orderWeight = 4;
                                $can_enroll = false;

                            }
                            $item->data5 = $percentage . ' %';
                        } else {
                            $item->data4 = 'Non-progressing';
                            $orderWeight = 2;
                            $can_enroll = false;
                        }
                    }
                } elseif ($item->end_date > now()->format('Y-m-d')) {
                        $item->data4 = 'Ongoing';
                        $orderWeight = 1;
                        if($item->last_date < now()->format('Y-m-d') ){
                            $can_enroll = false;
                        }
                }

                $item->image = $item->image ? asset('/') . $item->image : null;
                $item->order_weight = $orderWeight;
                $item->can_enroll = $can_enroll;
                return $item;
            });

            $courses = $courses->sortBy('order_weight')->values();
            $courses->makeHidden(['bible_name']);

            if(empty($courses)) {

                $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => "Empty course list "
                    ]
                ];
                return $result;
            }

            $mergedData = [

                [ 'category' => 'Bible Study', 'list' => $courses ]
                 
            ];

            return $this->outputer->code(200)
                        ->success($mergedData )
                        ->BibleVerse($bible_verse)
                        ->HomeBanner($banners)
                        ->LoginUser($login_user)
                        ->json();

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

    public function CompletedCourses(Request $request){
        try {

            $today= now();
            $today_string = now()->toDateString();

            $loggeed_user = Auth::user();

            $courses = Course::from(with(new Course)->getTable(). ' as a')
                ->join(with(new Batch)->getTable(). ' as b', 'a.id', 'b.course_id')
                ->join(with(new UserLMS)->getTable(). ' as c', 'b.id', 'c.batch_id')
                ->select(
                    'a.id', 'a.course_name as data1', 'a.course_creator as data2', 'a.thumbnail as image',
                    'b.id as batch_id', 'b.batch_name as data3', 'b.start_date', 'a.no_of_days',
                    'c.id as user_lms_id'
                )
                ->where('a.status', 1)
                ->where('b.status', 1)
                ->where('c.completed_status', 3)
                ->where('c.user_id', $loggeed_user['id']);

            if($request['search_word']){
                $courses->where('a.course_name','like',$request['search_word'].'%')
                        ->orwhere('a.course_creator','like',$request['search_word'].'%');
            }

            $courses=$courses->orderBy('b.end_date','asc')->get();
            
            $courses->transform(function ($item, $key) use($loggeed_user) {

                $readings_count = UserDailyReading::where('user_lms_id',$item['user_lms_id'])->count();
                $percentage= ( $readings_count/$item['no_of_days'])*100;

                $item->data4 = '';
                $item->data5 = '';

                if($readings_count>0 && $readings_count==$item->no_of_days){
                    $item->data4 = 'Completed';
                    $item->data5 = $percentage.' %';
                }
                   
                if ($item->image !== null) {
                    $item->image = asset('/') . $item->image;
                } else {
                    $item->image = null;
                }
                return $item;
            });
            $courses->makeHidden([ 'bible_name']);

            if(empty($courses)) {
                
                $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => "Empty course list "
                    ]
                ];
                return $result;
            }

            return $this->outputer->code(200)
                        ->success($courses )
                        ->json();

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

    public function CourseDetails(Request $request){
        
        try {

            if(auth('sanctum')->check()) {
               
            /*--------Authenticated User---------*/

                $user_id = auth('sanctum')->id();

                $login_user = User::select('id','image','timezone')->where('id',$user_id)->first();

                $userTimezone = $login_user->timezone ?? 'UTC';
                if($login_user->image !== null) {
                    $login_user->image = asset('/') . $login_user->image;
                }else{
                    $login_user->image = asset('/').'assets/images/user/user-dp.png';
                }

                $type = $request->input('type');

                $courses = Course::from(with(new Course)->getTable(). ' as a')
                            ->join(with(new Batch)->getTable(). ' as b' , 'a.id','b.course_id')
                            ->select('a.id','a.course_name','a.no_of_days','a.description','a.thumbnail',
                                'a.course_creator','a.creator_image','a.creator_designation',
                                'a.intro_video','a.intro_audio','a.intro_video_thumb','b.id as batch_id','b.batch_name','b.start_date','b.end_date','b.last_date',
                                DB::raw('DATE_FORMAT(b.start_date, "%b %d,%Y") as start_date'),
                                DB::raw('DATE_FORMAT(b.end_date, "%b %d,%Y") as end_date'),
                                DB::raw('DATE_FORMAT(b.last_date, "%b %d,%Y") as last_date')
                            )
                            ->where('b.id',$request['batch_id'])
                            ->get();

                $courses->transform(function ($item) use ($user_id,$type) {

                    if ($item->thumbnail !== null) {
                        $item->thumbnail = asset('/') . $item->thumbnail;
                    } else {
                        $item->thumbnail = null;
                    }

                    if ($item->creator_image !== null) {
                        $item->creator_image = asset('/') . $item->creator_image;
                    } else {
                        $item->creator_image = null;
                    }

                    if ($item->intro_video_thumb !== null) {
                        $item->intro_video_thumb = asset('/') . $item->intro_video_thumb;
                    } else {
                        $item->intro_video_thumb = null;
                    }
                    
                    $today = now()->startOfDay();
                    $courseStartDate = Carbon::parse($item->start_date)->startOfDay();

                    
                    if($today->gte($courseStartDate)) {
                        $item->course_start_status = 'started'; 
                    }else {
                        $item->course_start_status = 'not_started';
                    }

                    $last_course_content = CourseContent::select('day', 'created_at')->where('course_id', $item->id)
                                        ->whereHas('CourseDayVerse')
                                        ->orderBy('updated_at', 'desc')
                                        ->first();
                    if($last_course_content) {

                        $formattedCreatedAt = Carbon::parse($last_course_content['created_at'])->format('M d, Y');
                        $item->last_updated_data = $last_course_content['day'].' day content at '.$formattedCreatedAt;
                    }
                    
                    $item->last_updated_data = '';

                    $item->completion_percentage = 0; 

                    $user_lms = UserLMS::where('user_id',$user_id)
                                ->where('course_id',$item->id)
                                ->where('batch_id',$item->batch_id)
                                ->where('status',1)->first();
                    if($user_lms){

                        $item->user_enrolled = true;
                        $item->user_lms_id = $user_lms['id'];
                        if(Carbon::parse($item->start_date)->format('Y-m-d') > now()->format('Y-m-d')){
                            $item->allow_day_verse_read = false;
                            $item->course_content = [];

                        }else{
                            $item->allow_day_verse_read = true;
                            //$total_course_completed_days= UserDailyReading::where('user_lms_id',$user_lms['id'])->count();
                            //$item->completion_percentage = ($total_course_completed_days/$item->no_of_days)*100; 
                            $item->completion_percentage = $user_lms['progress']; 

                            //$current_day_number = Carbon::today()->diffInDays(Carbon::parse($item->start_date)) + 1; 
                            $userTimezone = auth()->user()->timezone ?? 'Pacific/Auckland';
                            $current_day_number = Carbon::today($userTimezone)
                                                    ->diffInDays(Carbon::parse($item->start_date,$userTimezone)) + 1; 

                            $course_content = CourseContent::select('day','id as course_content_id','course_id')
                                                ->where('course_id',$item->id)
                                                ->whereHas('CourseDayVerse') 
                                                ->where('day', '<=', $current_day_number)
                                                ->orderBy('day');
                            $upcoming_data = true;
                            if ($type ==1) {
                                
                                $largest_day_completed =UserDailyReading::where('user_lms_id',$user_lms['id'])
                                                        ->max('day');
                                if($largest_day_completed) {
                                    if($user_lms['completed_status']!=3){
                                        $course_content->where('day', '>', $largest_day_completed)->limit(5);
                                        
                                        $upcoming = $course_content->get();
                                        
                                        if($upcoming->isEmpty()) {

                                            $upcoming_data = false;      
                                        }
                                    }    
                                }else{
                                    $course_content->where('day', '>', 0)->limit(5);

                                }
                                $read_days = UserDailyReading::where('user_lms_id', $user_lms['id'])
                                            ->pluck('day')->toArray();

                                if($upcoming_data==false){
                                    $course_content = CourseContent::select('day','id as course_content_id','course_id')
                                                ->where('course_id',$item->id)
                                                ->whereHas('CourseDayVerse') 
                                                ->orderByDesc('day')
                                                ->limit(5);                                                
                                }else{
                                    if ($user_lms['completed_status'] != 3) {
                                        $course_content->whereNotIn('day', $read_days);
                                    }
                                }
                            }

                            $course_content = $course_content->get();

                            $course_content->makeHidden(['course_name', 'bible_name']);

                            $item->course_content = $course_content;

                            $item->course_content->transform(function ($content) use ($user_id, $user_lms) {

                                $day_verses =CourseDayVerse::select('book','chapter','verse_from','verse_to')
                                            ->where('course_content_id',$content['course_content_id'])->get();

                                $content->details = null;
                                if($day_verses->isNotEmpty()) {

                                    $day_verses->makeHidden(['testament_name']);
                                    
                                    $batchId = $user_lms['batch_id'];
                                    $day = $content['day'];

                                    $detailsArray = $day_verses->map(function ($day_verse) {
                                        return $day_verse->getFormattedCourseDaySections();
                                    })->toArray();


                                    $content->details = $detailsArray;
                                    
                                    $content->read_status = $day_verses->first()->isMarkedAsRead($user_id, $batchId, $day);

                                }
                                return $content;
                            });
                        }

                    }else{
                        $item->user_enrolled = false;
                        $item->user_lms_id = '';
                        $item->course_content = [];
                        $item->allow_day_verse_read = false;

                    }

                    return $item;
                });

                $courses->makeHidden([ 'bible_name']);

                if(empty($courses)) {
                    
                    $result = [
                        "status" => "error",
                        "metadata" => [],
                        "data" => [
                            "message" => "Empty course list "
                        ]
                    ];
                    return $result;
                }


            }else{

                $userTimezone = 'UTC';

                $login_user = (object) [
                    'id' => Null,
                    'image' => asset('assets/images/user/user-dp.png'),
                    'timezone' =>$userTimezone,
                    'user_name' => 'Guest User'
                ];


                $type = $request->input('type');

                $courses = Course::from(with(new Course)->getTable(). ' as a')
                            ->join(with(new Batch)->getTable(). ' as b' , 'a.id','b.course_id')
                            ->select('a.id','a.course_name','a.no_of_days','a.description','a.thumbnail',
                                'a.course_creator','a.creator_image','a.creator_designation',
                                'a.intro_video','a.intro_audio','a.intro_video_thumb','b.id as batch_id','b.batch_name','b.start_date','b.end_date','b.last_date',
                                DB::raw('DATE_FORMAT(b.start_date, "%b %d,%Y") as start_date'),
                                DB::raw('DATE_FORMAT(b.end_date, "%b %d,%Y") as end_date'),
                                DB::raw('DATE_FORMAT(b.last_date, "%b %d,%Y") as last_date')
                            )
                            ->where('b.id',$request['batch_id'])
                            ->get();

                $courses->transform(function ($item) use ($type) {

                    if ($item->thumbnail !== null) {
                        $item->thumbnail = asset('/') . $item->thumbnail;
                    } else {
                        $item->thumbnail = null;
                    }

                    if ($item->creator_image !== null) {
                        $item->creator_image = asset('/') . $item->creator_image;
                    } else {
                        $item->creator_image = null;
                    }

                    if ($item->intro_video_thumb !== null) {
                        $item->intro_video_thumb = asset('/') . $item->intro_video_thumb;
                    } else {
                        $item->intro_video_thumb = null;
                    }
                    
                    $today = now()->startOfDay();
                    $courseStartDate = Carbon::parse($item->start_date)->startOfDay();

                    
                    if($today->gte($courseStartDate)) {
                        $item->course_start_status = 'started'; 
                    }else {
                        $item->course_start_status = 'not_started';
                    }

                    $last_course_content = CourseContent::select('day', 'created_at')->where('course_id', $item->id)
                                        ->whereHas('CourseDayVerse')
                                        ->orderBy('updated_at', 'desc')
                                        ->first();
                    if($last_course_content) {

                        $formattedCreatedAt = Carbon::parse($last_course_content['created_at'])->format('M d, Y');
                        $item->last_updated_data = $last_course_content['day'].' day content at '.$formattedCreatedAt;
                    }
                    
                    $item->last_updated_data = '';
                    $item->completion_percentage = 0; 
                    $item->user_enrolled = false;
                    $item->user_lms_id = '';
                    $item->course_content = [];
                    $item->allow_day_verse_read = false;

                    return $item;
                });

                $courses->makeHidden([ 'bible_name']);

                if(empty($courses)) {
                    
                    $result = [
                        "status" => "error",
                        "metadata" => [],
                        "data" => [
                            "message" => "Empty course list "
                        ]
                    ];
                    return $result;
                }
            }



            return $this->outputer->code(200)
                        ->success($courses )
                        ->LoginUser($login_user)
                        ->json();


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

    public function EnrollBatch(Request $request){
        
        DB::beginTransaction();

        try {

            $a =  $request->validate([
                    'batch_id'      => 'required',
                ]);
            $batch = Batch::find($request['batch_id']);
            $user_id = Auth::user()->id;

            $inputData['user_id'] = $user_id;
            $inputData['course_id'] = $batch['course_id'];
            $inputData['batch_id'] = $batch['id'];
            $inputData['start_date'] = date('Y-m-d');

            $enrollment = UserLMS::create($inputData);
            DB::commit();

            $return['messsage']  =  'Success.You are enrolled to - '.$batch['batch_name'] .' batch.';
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

    public function CourseDayContent(Request $request){

        try {
            $course_content_id = $request['course_content_id'];
            $course_batch_id = $request['batch_id'];

           $course_day_content = CourseContent::select('id','course_id','day','text_description',
                'audio_file','website_link','image','documents')
                ->where('id', $course_content_id)
                ->where('status', 1)
                ->with(['CourseDayVerse' => function($query) {
                    $query->select('id', 'course_content_id', 'testament', 'book', 'chapter',
                            'verse_from', 'verse_to');
                }])
                ->with('CourseContentVideoLink','CourseContentSpotifyLink')
                ->first();
            if ($course_day_content) {
                $course_day_content->setCourseBatchId($course_batch_id);
                $course_day_content->append('completed_status');
            }

            if ($course_day_content) {
                $course_day_content->makeHidden(['verse_from_name', 'verse_to_name']);

                if ($course_day_content->image !== null) {
                    $course_day_content->image = asset('/') . $course_day_content->image;
                } else {
                    $course_day_content->image = null;
                }

                if ($course_day_content->documents !== null) {
                    $course_day_content->documents = asset('/') . $course_day_content->documents;
                } else {
                    $course_day_content->documents = null;
                }

                if ($course_day_content->audio_file !== null) {
                    $course_day_content->audio_file = asset('/') . $course_day_content->audio_file;
                } else {
                    $course_day_content->audio_file = null;
                }

                $course_day_content->CourseDayVerse->map(function($verse) {

                    $statements = HolyStatement::where('book_id',$verse->book)
                        ->where('chapter_id',$verse->chapter)
                        ->where('statement_id', '>=', $verse->verse_from)
                        ->where('statement_id', '<=', $verse->verse_to)
                        ->select('statement_id','statement_no','statement_text')
                        ->get()
                        ->map(function($statement) {
                        return [
                                'statement_id'      => $statement->statement_id,
                                'statement_no'      => ''.$statement->statement_no.'',
                                'statement_text'    => $statement->statement_text,
                                'note_marking'      => $statement->note_marking,
                                'bookmark_marking'  => $statement->bookmark_marking,
                                'color_marking'     => $statement->color_marking,
                            ];
                        });
                        $verse->statements = $statements;
                    return $verse;
                });
                // $course_day_content->CourseContentSpotifyLink->transform(function ($link) {
                //     $link->video_spotify_link = 'spotify:track:' . $link->video_spotify_link;
                //     return $link;
                // });
            }

            return $this->outputer->code(200)->success($course_day_content)->json();

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

    public function MarkAsRead(Request $request){
        
        DB::beginTransaction();

        try {

            $a =  $request->validate([
                    'user_lms_id'       => 'required',
                    'day'               => 'required',
                ]);

            $user_lms = UserLMS::find($request['user_lms_id']);
            $course = Course::find($user_lms['course_id']);

            if($course['no_of_days'] >= $request['day']){

                $already_marked = UserDailyReading::where('user_lms_id',$user_lms['id'])
                                ->where('day',$request['day'])->first();
                if($already_marked){

                    $result = [
                        "status" => "error",
                        "metadata" => [],
                        "data" => [
                            "message" => 'Failed.Already marked for this day.'
                        ]
                    ];
                    return $result;

                }else{

                    $readings_count = UserDailyReading::where('user_lms_id',$user_lms['id'])->count();
                    $percentage= ( $readings_count/$course['no_of_days'])*100;
                    
                    $inputData['user_lms_id'] = $request['user_lms_id'];
                    $inputData['day'] = $request['day'];
                    $inputData['date_of_reading'] = date('Y-m-d');

                    $marked = UserDailyReading::create($inputData);
                    DB::commit();

                    $user_lms = UserLMS::find($request['user_lms_id']);
                    $course = Course::find($user_lms['course_id']);
                    $readings_count = UserDailyReading::where('user_lms_id',$request['user_lms_id'])->count();
                    $percentage= ( $readings_count/$course['no_of_days'])*100;
                    if($readings_count==1){
                        $compl_status=2;
                        $user_lms['completed_status'] = $compl_status;
                    }
                    if($readings_count==$course['no_of_days']){
                        $compl_status=3;
                        $user_lms['completed_status'] = $compl_status;
                    }

                    $user_lms['progress'] = $percentage;
                    $user_lms->save();
                    $return['messsage']  =  'Success.Updated.';
                    return $this->outputer->code(200)->success($return)->json();
                }
            }else{

                $result = [
                    "status" => "error",
                    "metadata" => [],
                    "data" => [
                        "message" => 'Failed.Course days finished.'
                    ]
                ];
                return $result;
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

    public function CompleteBible(Request $request) {
        
        try {
            if (auth('sanctum')->check()) {
                /*--------Authenticated User---------*/
                $user_id = auth('sanctum')->id();
                $login_user = User::select('id', 'image', 'timezone')->where('id', $user_id)->first();
                $userTimezone = $login_user->timezone ?? 'UTC';

                $login_user->image = $login_user->image !== null
                    ? asset('/') . $login_user->image
                    : asset('/assets/images/user/user-dp.png');

            } else {
                $userTimezone = 'UTC';
                $login_user = (object) [
                    'id' => null,
                    'image' => asset('assets/images/user/user-dp.png'),
                    'timezone' => $userTimezone,
                    'user_name' => 'Guest User'
                ];
            }

            $sync_time = $request->sync_time ;

            $mergedData = collect([]);

            if (!empty($sync_time)) {

                $bible_id = env('DEFAULT_BIBLE');

                $last_change = BibleChange::where('bible_id',$bible_id)->orderBy('id', 'desc')->first();

                if($last_change['sync_time'] > $sync_time){
                
                    // Fetch testaments, books, and chapters with statements in a single query
                    $testaments = Testament::with([
                        'books.chapters.statements' => function ($query) {
                            $query->select('statement_id', 'chapter_id', 'statement_no', 'statement_heading', 'statement_text');
                        }
                    ])->where('bible_id', $bible_id)->get();

                    // Transform data
                    $mergedData = $testaments->map(function ($testament) {
                        $books = $testament->books->map(function ($book) {
                            $bookImg = BookImage::where('book_id', $book->book_id)->first();
                            $book_image = $bookImg !== null
                                ? asset('/') . $bookImg->image
                                : asset('/assets/images/logo.png');

                            $chapters = $book->chapters->map(function ($chapter) use($book) {
                                $statements = $chapter->statements->map(function ($statement) {
                                    return [
                                        'statement_id' => $statement->statement_id,
                                        'statement_no' => $statement->statement_no,
                                        'statement_heading' => $statement->statement_heading,
                                        'statement_text' => strip_tags(str_replace('<br>', "\n", $statement->statement_text))
                                    ];
                                });

                                return [
                                    'chapter_id' => $chapter->chapter_id,
                                    'chapter_no' => $chapter->chapter_no == 0 ? 'ആമുഖം' : $chapter->chapter_no,
                                    'chapter_name' => $chapter->chapter_name,
                                    'chapter_desc' => $chapter->chapter_desc,
                                    'book_id'    => $book->book_id,
                                    'book_name'    => $book->book_name,
                                    'statements' => $statements
                                ];
                            });

                            return [
                                'book_id' => $book->book_id,
                                'book_name' => $book->book_name,
                                'book_image' => $book_image,
                                'total_chapters' => $book->chapters->count() - 1,
                                'chapters' => $chapters
                            ];
                        });

                        return [
                            'category' => $testament->testament_name,
                            'list' => $books,
                        ];
                    });
                }
            }else{

                $bible_id = env('DEFAULT_BIBLE');

                // Fetch testaments, books, and chapters with statements in a single query
                    $testaments = Testament::with([
                        'books.chapters.statements' => function ($query) {
                            $query->select('statement_id', 'chapter_id', 'statement_no', 'statement_heading', 'statement_text');
                        }
                    ])->where('bible_id', $bible_id)->get();

                    // Transform data
                    $mergedData = $testaments->map(function ($testament) {
                        $books = $testament->books->map(function ($book) {
                            $bookImg = BookImage::where('book_id', $book->book_id)->first();
                            $book_image = $bookImg !== null
                                ? asset('/') . $bookImg->image
                                : asset('/assets/images/logo.png');

                            $chapters = $book->chapters->map(function ($chapter) use($book) {
                                $statements = $chapter->statements->map(function ($statement) {
                                    return [
                                        'statement_id' => $statement->statement_id,
                                        'statement_no' => $statement->statement_no,
                                        'statement_heading' => $statement->statement_heading,
                                        'statement_text' => strip_tags(str_replace('<br>', "\n", $statement->statement_text))
                                    ];
                                });

                                return [
                                    'chapter_id' => $chapter->chapter_id,
                                    'chapter_no' => $chapter->chapter_no == 0 ? 'ആമുഖം' : $chapter->chapter_no,
                                    'chapter_name' => $chapter->chapter_name,
                                    'chapter_desc' => $chapter->chapter_desc,
                                    'book_id'    => $book->book_id,
                                    'book_name'    => $book->book_name,
                                    'statements' => $statements
                                ];
                            });

                            return [
                                'book_id' => $book->book_id,
                                'book_name' => $book->book_name,
                                'book_image' => $book_image,
                                'total_chapters' => $book->chapters->count() - 1,
                                'chapters' => $chapters
                            ];
                        });

                        return [
                            'category' => $testament->testament_name,
                            'list' => $books,
                        ];
                    });
            }

            return $this->outputer->code(200)->success($mergedData->values())
                                ->LoginUser($login_user)->json();

        } catch (\Exception $e) {

            return [
                "status" => "error",
                "metadata" => [],
                "data" => ["message" => $e->getMessage()]
            ];
        }
    }

    public function BibleStudyV2(Request $request){
    
        try {

             if(auth('sanctum')->check()) {
               
            /*--------Authenticated User---------*/

                $user_id = auth('sanctum')->id();

                $login_user = User::select('id','image','timezone')->where('id',$user_id)->first();

                $userTimezone = $login_user->timezone ?? 'UTC';
                if($login_user->image !== null) {
                    $login_user->image = asset('/') . $login_user->image;
                }else{
                    $login_user->image = asset('/').'assets/images/user/user-dp.png';
                }

            }else{

                $userTimezone = 'UTC';

                $login_user = (object) [
                    'id' => Null,
                    'image' => asset('assets/images/user/user-dp.png'),
                    'timezone' =>$userTimezone,
                    'user_name' => 'Guest User'
                ];

            }

            $bible_id = env('DEFAULT_BIBLE');

            $testaments = Testament::with('books.chapters')->where('bible_id',$bible_id)->get();

            $mergedData = $testaments->map(function ($testament) {
                $books = $testament->books->map(function ($book) {
                    $bookImg = BookImage::where('book_id', $book->book_id)->first();

                    $book_image = $bookImg !== null 
                        ? asset('/') . $bookImg['image'] 
                        : asset('/') . 'assets/images/logo.png';

                    return [
                        'book_id' => $book->book_id,
                        'book_name' => $book->book_name,
                        'book_image' => $book_image,
                        'total_chapters' => $book->chapters->count() - 1,
                    ];
                });

                return [
                    'category' => $testament->testament_name,
                    'list' => $books,
                ];
            });

            return $this->outputer->code(200)->success($mergedData->values())
                                    ->LoginUser($login_user)->json();

        } catch (\Exception $e) {

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

    public function BibleStudyChapters(Request $request){

        try {

            if(auth('sanctum')->check()) {
               
            /*--------Authenticated User---------*/

                $user_id = auth('sanctum')->id();

                $login_user = User::select('id','image','timezone')->where('id',$user_id)->first();

                $userTimezone = $login_user->timezone ?? 'UTC';
                if($login_user->image !== null) {
                    $login_user->image = asset('/') . $login_user->image;
                }else{
                    $login_user->image = asset('/').'assets/images/user/user-dp.png';
                }

            }else{

                $userTimezone = 'UTC';

                $login_user = (object) [
                    'id' => Null,
                    'image' => asset('assets/images/user/user-dp.png'),
                    'timezone' =>$userTimezone,
                    'user_name' => 'Guest User'
                ];

            }

            $book_id = $request['book_id'];

            $chapters = Chapter::where('book_id',$book_id)
                        //->where('chapter_name', 'NOT LIKE', 'ആമുഖം')
                        ->get();

            $chapters->transform(function ($item, $key) {

                $book = Book::where('book_id',$item->book_id)->first();
                $item->book_name = $book->book_name;
                
                $statements = $item->statements()->get(['statement_id','statement_no','statement_heading',
                    'statement_text']);
                foreach ($statements as $statement) {
                    $statement->statement_text = str_replace('<br>', "\n", $statement->statement_text);
                    $statement->statement_text = strip_tags($statement->statement_text);
                }
                $item->statements = $statements;

                if ($item->chapter_no == 0) {
                    $item->chapter_no = 'ആമുഖം';
                }

                return $item;
            });

            
            return $this->outputer->code(200)->success($chapters)
                                ->LoginUser($login_user)->json();

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

    public function Notifications(Request $request){

        try {

            if(auth('sanctum')->check()) {
               
            /*--------Authenticated User---------*/

                $user_id = auth('sanctum')->id();

                $login_user = User::select('id','image','timezone')->where('id',$user_id)->first();

                $userTimezone = $login_user->timezone ?? 'UTC';
                if($login_user->image !== null) {
                    $login_user->image = asset('/') . $login_user->image;
                }else{
                    $login_user->image = asset('/').'assets/images/user/user-dp.png';
                }

                $clearedNotifications = Cache::get("user_{$user_id}_cleared_notifications", []);

                $notifications = Notification::select('id','title','type','redirection','description',
                                    'data')->whereNotIn('id', $clearedNotifications)->get();

                $notifications = $notifications->map(function ($notify) {
                        
                        if($notify->type==1 || $notify->type==2){

                            $notify->data = $notify->data !== null 
                                ? asset('/') . $notify['data'] 
                                : asset('/') . 'assets/images/logo.png';
                        }

                        return $notify;
                });

            }else{

                $userTimezone = 'UTC';

                $login_user = (object) [
                    'id' => Null,
                    'image' => asset('assets/images/user/user-dp.png'),
                    'timezone' =>$userTimezone,
                    'user_name' => 'Guest User'
                ];

                $notifications = Notification::select('id','title','type','redirection','description','data')
                                ->orderBy('id', 'desc')->limit(5)
                                ->get();

                $notifications = $notifications->map(function ($notify) {
                        
                        if($notify->type==1 || $notify->type==2){

                            $notify->data = $notify->data !== null 
                                ? asset('/') . $notify['data'] 
                                : asset('/') . 'assets/images/logo.png';
                        }

                        return $notify;
                });


            }


            return $this->outputer->code(200)->success($notifications)
                                ->LoginUser($login_user)
                                ->json();

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


    public function clearNotification(Request $request)
    {
        try {
            $userId = auth()->id();
            $notificationId  = $request->id;

            $clearedNotifications = Cache::get("user_{$userId}_cleared_notifications", []);

            if (in_array($notificationId, $clearedNotifications)) {
                $return['messsage']  =  'Failed.Notification already cleared';
            }

            $clearedNotifications[] = $notificationId;

            Cache::put("user_{$userId}_cleared_notifications", $clearedNotifications);

            $return['messsage']  =  'Success.Notification cleared';

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

    public function clearAllNotifications(Request $request)
    {
        try {
            $userId = auth()->id();

            $allNotificationIds = Notification::pluck('id')->toArray();

            Cache::put("user_{$userId}_cleared_notifications", $allNotificationIds);

             $return['messsage']  =  'Success.All Notification cleared';

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

    public function NotificationCacheClean(Request $request)
    {
        try {
            $userId = auth()->id();
            Cache::forget("user_{$userId}_cleared_notifications");

             $return['messsage']  =  'Success.All Notification cache cleared';

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

    public function DailyVerseCCacheClean(Request $request)
    {
        try {
            $date = date('Y-m-d');
            Cache::forget("daily_bible_verse_{$date}");
             $return['messsage']  =  'Success.daily verse cache cleared';

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

    public function AppBanners(Request $request){

        try {

            $banners = AppBanner::select('id','title','path')->where('status',2)->get();

            $banners = $banners->map(function ($item) {
                    
                $item->path = $item->path !== null 
                    ? asset('/') . $item->path
                    : asset('/') . 'assets/images/logo.png';

                return $item;
            });

            return $this->outputer->code(200)->success($banners)->json();

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

    public function TestApi(){


        $userLms = UserLMS::from(with(new UserLMS)->getTable() . ' as ulms')
            ->join(with(new Batch)->getTable() . ' as b', 'b.id', 'ulms.batch_id')
            ->select('ulms.*', 'b.id as batch_id', 'b.start_date as batch_start_date')
            ->where('ulms.status', 1)
            ->where('ulms.completed_status', '!=', 3)
            ->where('b.status', 1)
            ->whereDate('b.start_date', '<',  now())
            ->where('ulms.user_id',1)
            ->get();
        
        foreach ($userLms as $value) {
            $user = User::find($value->user_id);
            $userTimeZone = $user->timezone ?? 'UTC';
            $localCurrentTime = Carbon::now($userTimeZone)->format('H:i');
            $desiredTime = '17:50';

            // if ($localCurrentTime !== $desiredTime) {
            //     continue;
            // }
            $courses = Course::from(with(new Course)->getTable() . ' as c')
                ->join(with(new CourseContent)->getTable() . ' as cc', 'c.id', '=', 'cc.course_id')
                ->select('cc.*', 'c.id as course_id')
                ->where('c.id', $value->course_id)
                ->orderBy('cc.day', 'desc')
                ->limit(1)->first();

            if ($courses) {
                $userReadings = UserDailyReading::where('user_lms_id', $value->id)
                    ->orderBy('day', 'desc')
                    ->limit(1)->first();

                $batchDetails = Batch::find($value->batch_id);

                if ($userReadings) {
                    $readingDate = Carbon::parse($userReadings->date_of_reading, 'UTC')
                                    ->setTimezone($userTimeZone);
                    $today = Carbon::now($userTimeZone);

                    if ($today->diffInDays($readingDate) >= 3) {
                        // Notify user inactivity for 3 days
                        $this->sendNotification($value, $batchDetails, 'inactivity', $userTimeZone);
                    }
                } else {
                    $startDate = Carbon::parse($batchDetails->start_date, 'UTC')
                                    ->setTimezone($userTimeZone);
                    $today = Carbon::now($userTimeZone);

                    if ($today->diffInDays($startDate) >= 3) {
                        // Notify user has not started course yet
                        $this->sendNotification($value, $batchDetails, 'not_started', $userTimeZone);
                    }
                }
            }
        }   
    }


    private function sendNotification($userLms, $batchDetails, $type, $userTimeZone)
    {
        $pushData = [];
        
        $pushData['tokens'] = User::whereNotNull('refresh_token')
            ->where('id', $userLms->user_id)
            ->pluck('refresh_token')
            ->toArray();

        if ($type === 'inactivity') {
            $pushData['title'] = 'We Miss You at batch - ' .$batchDetails->batch_name.' : ' . $batchDetails->course->course_name;
            $pushData['body'] = 'It’s been a few days since you last visited.' . PHP_EOL . 'Jump back in and continue your journey to success!';
        } elseif ($type === 'not_started') {
            $pushData['title'] = 'Your Learning Journey Awaits!';
            $pushData['body'] = 'You’re all set to start course ' . $batchDetails->course->course_name . '.' . PHP_EOL . 'Get a head start and dive into your first lesson now.';
        }

        $pushData['route'] = 'CourseInactivity';
        $pushData['id'] = $userLms->batch_id;
        $pushData['category'] = 'CourseInactivity';
        $pushData['data1'] = $batchDetails->course->course_name;
        $pushData['data2'] = Carbon::parse($batchDetails->start_date, 'UTC')->setTimezone($userTimeZone)->toDateTimeString();
        $pushData['data3'] = Carbon::parse($batchDetails->last_date, 'UTC')->setTimezone($userTimeZone)->toDateTimeString();
        $pushData['data4'] = null;
        $pushData['data5'] = null;
        $pushData['image1'] = null;

        if (!empty($push_data['tokens'])) {
            $pusher = new NotificationPusher();
            //$pusher->push($pushData);

            Log::channel('notification_log')->info("======>>>>>Notifications sent for user - " . now() . "  ======>>>>>\n" . json_encode($pushData['tokens']));    
        }
    }
}
