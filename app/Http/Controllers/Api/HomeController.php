<?php

namespace App\Http\Controllers\Api;

use DB;
use Auth;
use App\Helpers\Outputer;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Course;
use App\Models\Testament;
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

            $courses = Course::select('id','course_name as data1','thumbnail as image','course_creator as data2')
                        ->where('status',1);

            if($request['search_word']){
                $courses->where('course_name','like',$request['search_word'].'%')
                        ->orwhere('course_creator','like',$request['search_word'].'%');
            }

            if($request['length']){
                $courses->take($request['length']);
            }

            $courses=$courses->orderBy('course_name','asc')->get();

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
                $return['result']=  "Empty messages list ";
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
