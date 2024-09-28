<?php

namespace App\Http\Controllers\Api;

use DB;
use Auth;
use Carbon\Carbon;
use App\Helpers\Outputer;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Book;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Chapter;
use App\Models\UserLMS;
use App\Models\Testament;
use App\Models\BookImage;
use App\Models\CourseContent;
use App\Models\HolyStatement;
use App\Models\CourseDayVerse;
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

            $today= now();
            $today_string = now()->toDateString();

            /*--------Authenticated User---------*/

            $login_user = User::select('id','image')->where('id',Auth::user()->id)->first();

            if($login_user->image !== 'null') {
                $login_user->image = asset('/') . $login_user->image;
            }else{
                $login_user->image = asset('/').'assets/images/user/user-dp.png';
            }


            /*---------Daily Bible verse----------*/
            
            $bible_verse =  DailyBibleVerse::from(with(new DailyBibleVerse)->getTable(). ' as a')
                            ->select('a.id','a.verse_id')
                            ->whereRaw("DATE_FORMAT(date, '%m-%d') = DATE_FORMAT('$today_string', '%m-%d')")
                            ->where('status', 1)
                            ->first();
            if (!$bible_verse) {
                $bible_verse = DailyBibleVerse::from(with(new DailyBibleVerse)->getTable(). ' as a')
                            ->select('a.id','a.verse_id')
                            ->where('status', 1)
                            ->inRandomOrder()
                            ->first();
            }
            if($bible_verse) {
                $statement = HolyStatement::where('statement_id',$bible_verse->verse_id)->first();
                $bible_verse->data1 = $statement->statement_text;
                $bible_verse->data2 = $statement->book->book_name.'  '.$statement->chapter->chapter_no.' : '.$statement->statement_no;
            }
            $bible_verse->makeHidden(['bible_name','testament_name','book_name','chapter_name','verse_no','theme_name']);
            
            /*---------Courses----------*/

            $courses = Course::from(with(new Course)->getTable(). ' as a')
                        ->join(with(new Batch)->getTable(). ' as b' , 'a.id','b.course_id')
                        ->select('a.id','b.id as batch_id','b.start_date','a.no_of_days','a.thumbnail as image',
                            'a.course_name as data1','a.course_creator as data2','b.batch_name as data3')
                         ->where('b.end_date', '>', now()->subDay()->format('Y-m-d'))
                        ->where('a.status',1)
                        ->where('b.status',1);

            if($request['search_word']){
                $courses->where('a.course_name','like',$request['search_word'].'%')
                        ->orwhere('a.course_creator','like',$request['search_word'].'%');
            }

            if($request['length']){
                $courses->take($request['length']);
            }

            $courses=$courses->orderBy('b.end_date','asc')->get();

            $courses->transform(function ($item, $key) {

                if($item->start_date >= now()->format('Y-m-d')){
                    $item->data4 = 'New Batch';
                    $item->data5 = '0%';
                }else{
                    $item->data4 = '';

                    $startDate = Carbon::parse($item->start_date);
                    $currentDate = now();
                    $daysDifference = $startDate->diffInDays($currentDate);
                    $percentage = ceil(($daysDifference/$item->no_of_days)*100);

                    $item->data5 = $percentage.'%' ;

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
                $return['result']=  "Empty course list ";
                return $this->outputer->code(422)->error($return)->json();
            }

            $mergedData = [

                [ 'category' => 'Bible Study', 'list' => $courses ]
                 
            ];

            return $this->outputer->code(200)
                        ->success($mergedData )
                        ->BibleVerse($bible_verse)
                        ->LoginUser($login_user)
                        ->json();

        }catch (\Exception $e) {

            $return['result']=$e->getMessage();
            return $this->outputer->code(422)->error($return)->json();
        }
    }

    public function AllCourses(Request $request){
        try {

            $today= now();
            $today_string = now()->toDateString();

            $courses = Course::from(with(new Course)->getTable(). ' as a')
                        ->join(with(new Batch)->getTable(). ' as b' , 'a.id','b.course_id')
                        ->select('a.id','a.course_name as data1','a.course_creator as data2','a.thumbnail as image',
                            'b.id as batch_id','b.batch_name as data3','b.start_date','a.no_of_days')
                         ->where('b.end_date', '>', now()->subDay()->format('Y-m-d'))
                        ->where('a.status',1)
                        ->where('b.status',1);

            if($request['search_word']){
                $courses->where('a.course_name','like',$request['search_word'].'%')
                        ->orwhere('a.course_creator','like',$request['search_word'].'%');
            }

            $courses=$courses->orderBy('b.end_date','asc')->get();

            $courses->transform(function ($item, $key) {

                if($item->start_date >= now()->format('Y-m-d')){
                    $item->data4 = 'New Batch';
                    $item->data5 = 0;
                }else{
                    $item->data4 = '';

                    $startDate = Carbon::parse($item->start_date);
                    $currentDate = now();
                    $daysDifference = $startDate->diffInDays($currentDate);
                    $percentage = ceil(($daysDifference/$item->no_of_days)*100);

                    $item->data5 = $percentage;

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
                $return['result']=  "Empty course list ";
                return $this->outputer->code(422)->error($return)->json();
            }

            return $this->outputer->code(200)
                        ->success($courses )
                        ->json();

        }catch (\Exception $e) {

            $return['result']=$e->getMessage();
            return $this->outputer->code(422)->error($return)->json();
        }
    }

    public function CourseDetails(Request $request){
        try {

            $userId = Auth::user()->id;

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

            $courses->transform(function ($item) use ($userId,$type) {

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

                $user_lms = UserLMS::where('user_id',$userId)
                            ->where('course_id',$item->id)
                            ->where('batch_id',$item->batch_id)
                            ->where('status',1)->first();
                if($user_lms){

                    $item->user_enrolled = true;
                    $item->user_lms_id = $user_lms['id'];

                    //$total_course_completed_days= UserDailyReading::where('user_lms_id',$user_lms['id'])->count();
                    //$item->completion_percentage = ($total_course_completed_days/$item->no_of_days)*100; 
                    $item->completion_percentage = $user_lms['progress']; 


                    $course_content = CourseContent::select('day','id as course_content_id','course_id')
                                        ->where('course_id',$item->id)
                                        ->whereHas('CourseDayVerse') 
                                        ->orderBy('day');

                    if ($type ==1) {
                        
                        $largest_day_completed =UserDailyReading::where('user_lms_id',$user_lms['id'])->max('day');

                        if ($largest_day_completed) {

                            $course_content->where('day', '>', $largest_day_completed)->limit(5);
                        }
                    }

                    $course_content = $course_content->get();

                    $course_content->makeHidden(['course_name', 'bible_name']);

                    $item->course_content = $course_content;

                    $item->course_content->transform(function ($content) use ($userId, $user_lms) {

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
                            
                            $content->read_status = $day_verses->first()->isMarkedAsRead($userId, $batchId, $day);

                        }
                        return $content;
                    });




                }else{
                    $item->user_enrolled = false;
                    $item->course_content = [];
                    $item->user_lms_id = '';

                }

                return $item;
            });

            $courses->makeHidden([ 'bible_name']);

            if(empty($courses)) {
                $return['result']=  "Empty course list ";
                return $this->outputer->code(422)->error($return)->json();
            }

            return $this->outputer->code(200)
                        ->success($courses )
                        ->json();

        }catch (\Exception $e) {

            $return['result']=$e->getMessage();
            return $this->outputer->code(422)->error($return)->json();
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
            $return['result']=$e->getMessage();
            return $this->outputer->code(422)->error($return)->json();
        }
    }

    public function CourseDayContent(Request $request){

        try {
            $course_content_id = $request['course_content_id'];

           $course_day_content = CourseContent::select('id','course_id','day','text_description','video_link',
                'audio_file','spotify_link','website_link','image','documents')
                ->where('id', $course_content_id)
                ->where('status', 1)
                ->with(['CourseDayVerse' => function($query) {
                    $query->select('id', 'course_content_id', 'testament', 'book', 'chapter',
                            'verse_from', 'verse_to');
                }])
                ->first();

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
                    $statements = HolyStatement::where('statement_id', '>=', $verse->verse_from)
                        ->where('statement_id', '<=', $verse->verse_to)
                        ->pluck('statement_text')
                        ->toArray();

                    $verse->statements = implode(",\n", $statements);
                    return $verse;
                });
            }

            return $this->outputer->code(200)->success($course_day_content)->json();

        }catch (\Exception $e) {

            $return['result']=$e->getMessage();
            return $this->outputer->code(422)->error($return)->json();
        }
    }

    public function MarkAsRead(Request $request){
        
        DB::beginTransaction();

        try {

            $a =  $request->validate([
                    'user_lms_id'       => 'required',
                    'day'               => 'required',
                ]);

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


        }catch (\Exception $e) {

            DB::rollBack();
            $return['result']=$e->getMessage();
            return $this->outputer->code(422)->error($return)->json();
        }
    }

    public function BibleStudy(Request $request){

        try {

            $testament_id = $request['testament_id'];

            $testament = Testament::with('books.chapters')->where('testament_id',$testament_id)->first();

            $books = $testament->books->map(function ($book) {

                $bookImg = BookImage::where('book_id',$book->book_id)->first();
                
                if($bookImg !== null) {
                    $book_image= asset('/') . $bookImg['image'];
                }else{
                    $book_image= asset('/').'assets/images/logo.png';
                }

                return [
                    'book_id' => $book->book_id,
                    'book_name' => $book->book_name,
                    'book_image' => $book_image,
                    'total_chapters' => $book->chapters->count()-1
                ];
            });
            
            return $this->outputer->code(200)->success($books)->json();

        }catch (\Exception $e) {

            $return['result']=$e->getMessage();
            return $this->outputer->code(422)->error($return)->json();
        }
    }

    public function BibleStudyChapters(Request $request){

        try {

            $book_id = $request['book_id'];

            $chapters = Chapter::where('book_id',$book_id)->where('chapter_name', 'NOT LIKE', 'ആമുഖം')->get();

            $chapters->transform(function ($item, $key) {

                $book = Book::where('book_id',$item->book_id)->first();
                $item->book_name = $book->book_name;
                
                $statements = $item->statements()->get(['statement_id','statement_text']);
                $item->statements = $statements;

                return $item;
            });

            
            return $this->outputer->code(200)->success($chapters)->json();

        }catch (\Exception $e) {

            $return['result']=$e->getMessage();
            return $this->outputer->code(422)->error($return)->json();
        }
    }

}
