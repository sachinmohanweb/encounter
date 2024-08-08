<?php

namespace App\Http\Controllers\Api;

use DB;
use Auth;
use App\Helpers\Outputer;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Batch;
use App\Models\Course;
use App\Models\UserLMS;
use App\Models\Testament;
use App\Models\CourseContent;
use App\Models\HolyStatement;
use App\Models\DailyBibleVerse;

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
            // $day = $today->format('d');
            // $month = $today->format('m');
            // $monthDay = $today->format('m-d');
            // $todayFormatted = date('d/m/Y');


            /*--------Authenticated User---------*/

            $login_user = User::select('id',DB::raw('"null" as image'))->where('id',Auth::user()->id)->first();
            if (!$login_user->image) {
                if ($login_user->image !== null) {
                    $login_user->image = asset('/') . $login_user->image;
                }
            }

            /*---------Daily Bible verse----------*/
            
            $bible_verse =  DailyBibleVerse::from(with(new DailyBibleVerse)->getTable(). ' as a')
                            ->select('a.id',DB::raw('"null" as image'),DB::raw('"null" as data2'),'a.verse_id')
                            ->whereRaw("DATE_FORMAT(date, '%m-%d') = DATE_FORMAT('$today_string', '%m-%d')")
                            ->where('status', 1)
                            ->first();
            if (!$bible_verse) {
                $bible_verse = DailyBibleVerse::from(with(new DailyBibleVerse)->getTable(). ' as a')
                            ->select('a.id',DB::raw('"null" as image'),DB::raw('"null" as data2'),'a.verse_id')
                            ->where('status', 1)
                            ->inRandomOrder()
                            ->first();
            }
            if($bible_verse) {
                $statement = HolyStatement::where('statement_id',$bible_verse->verse_id)->first();
                $bible_verse->data1 = $statement->statement_text;
            }
            $bible_verse->makeHidden(['bible_name','testament_name','book_name','chapter_name','verse_no','theme_name']);
            /*---------Courses----------*/

            $courses = Course::from(with(new Course)->getTable(). ' as a')
                        ->join(with(new Batch)->getTable(). ' as b' , 'a.id','b.course_id')
                        ->select('a.id','a.course_name as data1','a.course_creator as data2','a.thumbnail as image')
                        ->where('a.status',1);

            if($request['search_word']){
                $courses->where('a.course_name','like',$request['search_word'].'%')
                        ->orwhere('a.course_creator','like',$request['search_word'].'%');
            }

            if($request['length']){
                $courses->take($request['length']);
            }

            $courses=$courses->orderBy('a.course_name','asc')->get();

            $courses->transform(function ($item, $key) {

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

    public function CourseDetails(Request $request){
        try {

            $courses = Course::from(with(new Course)->getTable(). ' as a')
                        ->join(with(new Batch)->getTable(). ' as b' , 'a.id','b.course_id')
                        ->select('a.id','a.course_name','a.no_of_days','a.description','a.thumbnail',
                            'a.course_creator',DB::raw("'null' as course_creator_image"),'b.id as batch_id','b.batch_name',
                            'b.start_date','b.end_date','b.last_date')
                        ->where('a.id',$request['id'])
                        ->with(['CourseContents' => function($query) {
                            $query->select('id as day_id','day', 'text_description','course_id')
                                    ->where('status', 1);
                        }])
                        ->get();


            $courses->transform(function ($item, $key) {

                if ($item->thumbnail !== null) {
                    $item->thumbnail = asset('/') . $item->thumbnail;
                } else {
                    $item->thumbnail = null;
                }
                $item->CourseContents->makeHidden(['course_name', 'bible_name']);

                $item->course_creator_image = asset('/').'assets/images/user/user-dp.png';
                $user_lms = UserLMS::where('user_id',Auth::user()->id)
                            ->where('course_id',$item->id)
                            ->where('batch_id',$item->batch_id)
                            ->where('status',1)->count();
                if($user_lms==1){
                    $item->user_enrolled = true;
                }else{
                    $item->user_enrolled = false;
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
            $inputData['start_date'] = $batch['start_date'];
            $inputData['end_date'] = $batch['end_date'];
            $inputData['progress'] = '0%';
            $inputData['completed_status'] = 1;
            $inputData['completed_status'] = 1;

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
            $course_id = $request['course_id'];

            $course_day_content = CourseContent::where('course_id',$course_id)
                        ->where('status',1)
                        ->with(['CourseDayVerse'])
                        ->get();


            return $this->outputer->code(200)->success($course_day_content)->json();

        }catch (\Exception $e) {

            $return['result']=$e->getMessage();
            return $this->outputer->code(422)->error($return)->json();
        }
    }

    public function BibleStudy(Request $request){

        try {

            $testament_id = $request['testament_id'];

            $testament = Testament::with('books.chapters')->where('testament_id',$testament_id)->first();

            $books = $testament->books->map(function ($book) {
                return [
                    'book_id' => $book->book_id,
                    'book_name' => $book->book_name,
                    'total_chapters' => $book->chapters->count(),
                    'chapters' => $book->chapters->map(function ($chapter) {
                        return [
                            'chapter_id' => $chapter->chapter_id,
                            'chapter_number' => $chapter->chapter_no,
                            'statements' => $chapter->statements->map(function ($statement,$chapter) {
                                return [
                                    'statement_id' => $statement->statement_id,
                                    'statement_no' => $statement->statement_no,
                                    'statement_heading' => $statement->statement_heading,
                                    'statement_text' => $statement->statement_text,
                                ];
                            }),
                        ];
                    })
                ];
            });
            
            return $this->outputer->code(200)->success($books)->json();

        }catch (\Exception $e) {

            $return['result']=$e->getMessage();
            return $this->outputer->code(422)->error($return)->json();
        }
    }

}
