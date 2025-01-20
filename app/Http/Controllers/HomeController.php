<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

use DB;

use App\Models\User;
use App\Models\Batch;
use App\Models\Course;
use App\Models\GotQuestion;
use App\Models\CourseContent;
use App\Models\CourseDayVerse;
use App\Models\DailyBibleVerse;

class HomeController extends Controller
{
    
    public function index() : View
    {
        return view('homepage',[]);
    }
    public function admin_index() : View
    {
        return view('index',[]);
    }

    public function admin_dashboard() : View
    {
        $users = User::where('status',1)->count();
        
        $course_batches = Course::from(with(new Course)->getTable() . ' as a')
                        ->join(with(new Batch)->getTable() . ' as b', 'a.id', '=', 'b.course_id')
                        ->where('a.status', 1)
                        ->where('b.status', 1)
                        ->whereExists(function ($query) {
                            $query->select(DB::raw(1))
                                ->from(with(new CourseContent)->getTable() . ' as cc')
                                ->join(with(new CourseDayVerse)->getTable() . ' as cdv', 'cc.id', '=', 'cdv.course_content_id')
                                ->whereColumn('cc.course_id', 'a.id')
                                ->where('cc.status', 1)
                                ->where('cdv.status', 1);
                        })
                        ->distinct('b.id')
                        ->count('b.id');
        $gq_questions = GotQuestion::where('status',1)->count();

        $daily_verse = DailyBibleVerse::where('status',1)->count();

        return view('dashboard.index',['users'=>$users,'course_batches'=>$course_batches,
                'gq_questions'=>$gq_questions,'daily_verse'=>$daily_verse]);
    }  
}
