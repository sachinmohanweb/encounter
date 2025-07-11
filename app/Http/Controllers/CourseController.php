<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

use DB;
use Session;
use Exception;

use App\Models\User;
use App\Models\Batch;
use App\Models\Course;
use App\Models\UserLMS;
use App\Models\CourseContent;
use App\Models\CourseDayVerse;
use App\Models\CourseContentLink;

use App\Models\Bible;
use App\Models\Book;
use App\Models\Chapter;
use App\Models\Testament;
use App\Models\HolyStatement;

use App\Notifications\NotificationPusher; 
use App\Jobs\SendPushNotification;

class CourseController extends Controller
{
    
    public function CourseList() : View
    {
        $courses = Course::with('BatchDetails')
                    ->orderByRaw('COALESCE(course_order, 9999) ASC')
                    ->orderBy('created_at','desc')
                    ->get();
        return view('courses.Courselist',compact('courses'));
    }

    public function AddCourse() : View
    {
        return view('courses.AddCourse',[]);
    }
    
    public function SaveCourse(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $a =  $request->validate([
                'bible_id' => 'required',
                'course_name' => 'required',
                'course_creator' => 'required',
                'no_of_days' => 'required',
                'status' => 'required',
                
            ]);

            $inputData = $request->all();

            if($request['thumbnail']){

                $fileName =str_replace(' ', '_',$request->course_name).'_' . time() . '.' .$request['thumbnail']->extension();
                $request->thumbnail->storeAs('courses', $fileName);
                $inputData['thumbnail'] = 'storage/courses/'.$fileName;
            }

            if($request['creator_image']){

                $fileName =str_replace(' ', '_',$request->course_creator).'_' . time() . '.' .$request['creator_image']->extension();
                $request->creator_image->storeAs('courses_creator', $fileName);
                $inputData['creator_image'] = 'storage/courses_creator/'.$fileName;
            }

            $course = Course::create($inputData);
            DB::commit();
             
            return redirect()->route('admin.course.details',['id' => $course['id']])
                            ->with('success',"Success! New Course has been successfully added. Now, let's create course content.");
        }catch (Exception $e) {

            DB::rollBack();
            $message = $e->getMessage();
            return back()->withInput()->withErrors(['message' =>  $e->getMessage()]);;
        }
    }

    public function CourseStatusChange(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $course = Course::find($request['id']);
            if($course->status=='Active'){
                $course->status=2;
            }else{
                $course->status=1;
            }
            $course->save();;
            DB::commit();

            return response()->json(['success' => true ,'msg' => 'course status updated','status' =>$course['status']]);

        }catch (Exception $e) {

            DB::rollBack();
            $message = $e->getMessage();

            return response()->json(['success' => false,'msg' => $e->getMessage()]);
        }
    }

    public function CourseDetails($id) : View
    {
        $course = Course::where('id',$id)->first();

        $batches = Batch::where('course_id',$id)->get(); 

        return view('courses.CourseDetail',compact('course','batches'));

    }

    public function EditCourse($id) : View
    {
        $course = Course::where('id',$id)->first();

        $bibles = Bible::get(); 

        return view('courses.EditCourse',compact('course','bibles'));

    }

    public function UpdateCourse(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {

            $course = Course::find($request->id);

            $a =  $request->validate([
                'bible_id' => 'required',
                'course_name' => 'required',
                'course_creator' => 'required',
                'no_of_days' => 'required',
                'status' => 'required',
                
            ]);

            $inputData = $request->all();

            if($request['thumbnail']){

                $fileName =str_replace(' ', '_',$request->course_name).'_' . time() . '.' .$request['thumbnail']->extension();
                $request->thumbnail->storeAs('courses', $fileName);
                $inputData['thumbnail'] = 'storage/courses/'.$fileName;
            }

            if($request['creator_image']){

                $fileName =str_replace(' ', '_',$request->course_creator).'_' . time() . '.' .$request['creator_image']->extension();
                $request->creator_image->storeAs('courses_creator', $fileName);
                $inputData['creator_image'] = 'storage/courses_creator/'.$fileName;
            }

            $course->update($inputData);
            DB::commit();

            return redirect()->route('admin.course.details',['id' => $course['id']])
                            ->with('success',"Success! Course has been successfully updated.");
        }catch (Exception $e) {

            DB::rollBack();
            $message = $e->getMessage();
            return back()->withInput()->withErrors(['message' =>  $e->getMessage()]);;
        }
    }

    public function CourseContent($id) : View
    {
        $course = Course::where('id',$id)->first();
        $activeTab = request()->query('active_tab', 1);

        return view('courses.CourseContent',compact('course','activeTab'));

    }

    public function AddCourseContent($course_id,$day) : View
    {
        $course = Course::find($course_id);
        return view('courses.AddCourseContent',compact('course_id','day','course'));
    }

    public function SaveCourseContent(Request $request): RedirectResponse
    {

        DB::beginTransaction();
        try {
            $a =  $request->validate([
                'course_id' => 'required',
                'day' => 'required',
            ]);


            $inputData = $request->all();
            unset($inputData['video_link'], $inputData['spotify_link']);

            if($request['image']){

                $time = microtime(true);
                $timeMilliseconds = round($time * 1000);
                $timeString = (string) $timeMilliseconds;

                $fileName =$timeString.'.'.$request['image']->extension();
                $request->image->storeAs('course_contents/images', $fileName);
                $inputData['image'] = 'storage/course_contents/images/'.$fileName;
            }
            if($request['documents']){

                $time = microtime(true);
                $timeMilliseconds = round($time * 1000);
                $timeString = (string) $timeMilliseconds;

                $fileName =$timeString.'.'.$request['documents']->extension();
                $request->documents->storeAs('course_contents/documents', $fileName);
                $inputData['documents'] = 'storage/course_contents/documents/'.$fileName;
            }
            if($request['audio_file']){

                $time = microtime(true);
                $timeMilliseconds = round($time * 1000);
                $timeString = (string) $timeMilliseconds;

                $fileName =$timeString.'.'.$request['audio_file']->extension();
                $request->audio_file->storeAs('course_contents/audio', $fileName);
                $inputData['audio_file'] = 'storage/course_contents/audio/'.$fileName;
            }

            $course_content = CourseContent::create($inputData);
            
            $video_links   = array_filter($request['video_link']);
            $video_titles = $request->input('video_title');
            $video_descriptions = $request->input('video_description');
            $video_thumbnails = $request->file('video_thumbnail');

            $spotify_links = array_filter($request['spotify_link']);
            $spotify_titles = $request->input('spotify_title');
            $spotify_descriptions = $request->input('spotify_description');

            if(count($video_links) > 0) {
                foreach($video_links as $key=>$value){
                    $video_data['course_content_id'] = $course_content->id;
                    $video_data['type'] = '1';

                    $video_data['title'] = $video_titles[$key];
                    $video_data['description'] = $video_descriptions[$key];
                    $video_data['video_spotify_link'] = $value;
                    
                    // Handle thumbnail upload for video
                    if(isset($video_thumbnails[$key]) && $video_thumbnails[$key]) {
                        $time = microtime(true);
                        $timeMilliseconds = round($time * 1000);
                        $timeString = (string) $timeMilliseconds;

                        $fileName = $timeString . '_' . $key . '.' . $video_thumbnails[$key]->extension();
                        $video_thumbnails[$key]->storeAs('course_contents/video_thumbnails', $fileName);
                        $video_data['thumbnail'] = 'storage/course_contents/video_thumbnails/' . $fileName;
                    }
                    
                    $course_content_video = CourseContentLink::create($video_data);
                }
            }

            if(count($spotify_links) > 0) {
                foreach($spotify_links as $key1=>$value1){
                    $spotify_data['course_content_id'] = $course_content->id;
                    $spotify_data['type'] = '2';
                    
                    $spotify_data['title'] = $spotify_titles[$key1];
                    $spotify_data['description'] = $spotify_descriptions[$key1];
                    $spotify_data['video_spotify_link'] = $value1;   

                    $course_content_spotify = CourseContentLink::create($spotify_data);
                }
            }

            DB::commit();
             
            return redirect()->route('admin.course.content',['id' => $request['course_id']])
                            ->with('success',"Success! New Course Content has been successfully added.");
        }catch (Exception $e) {

            DB::rollBack();
            $message = $e->getMessage();
            return back()->withInput()->withErrors(['message' =>  $e->getMessage()]);;
        }
    }

    public function EditCourseContent($content_id) : View
    {      
        $content = CourseContent::where('id',$content_id)->with('CourseContentVideoLink')->first();
        $course = Course::where('id',$content->course_id)->first();

        return view('courses.EditCourseContent',compact('content','course'));
    }

    public function UpdateCourseContent(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {

            $content = CourseContent::find($request->id);

            $a =  $request->validate([
                'course_id' => 'required',
                'day' => 'required',                
            ]);

            // $video_links   = array_filter($request['video_link']);
            // $spotify_links = array_filter($request['spotify_link']);

            $inputData = $request->all();
            unset($inputData['video_link'], $inputData['spotify_link']);

            if($request['image']){

                $time = microtime(true);
                $timeMilliseconds = round($time * 1000);
                $timeString = (string) $timeMilliseconds;

                $fileName =$timeString.'.'.$request['image']->extension();
                $request->image->storeAs('course_contents/images', $fileName);
                $inputData['image'] = 'storage/course_contents/images/'.$fileName;
            }
            if($request['documents']){

                $time = microtime(true);
                $timeMilliseconds = round($time * 1000);
                $timeString = (string) $timeMilliseconds;

                $fileName =$timeString.'.'.$request['documents']->extension();
                $request->documents->storeAs('course_contents/documents', $fileName);
                $inputData['documents'] = 'storage/course_contents/documents/'.$fileName;
            }
            if($request['audio_file']){

                $time = microtime(true);
                $timeMilliseconds = round($time * 1000);
                $timeString = (string) $timeMilliseconds;

                $fileName =$timeString.'.'.$request['audio_file']->extension();
                $request->audio_file->storeAs('course_contents/audio', $fileName);
                $inputData['audio_file'] = 'storage/course_contents/audio/'.$fileName;
            }

            $content->update($inputData);

            CourseContentLink::where('course_content_id', $request->id)->delete();

            $video_links   = array_filter($request['video_link']);
            $video_titles = $request->input('video_title');
            $video_descriptions = $request->input('video_description');
            $video_thumbnails = $request->file('video_thumbnail');
            $existing_video_thumbnails = $request->input('existing_video_thumbnail');

            $spotify_links = array_filter($request['spotify_link']);
            $spotify_titles = $request->input('spotify_title');
            $spotify_descriptions = $request->input('spotify_description');

            if(count($video_links) > 0) {
                foreach($video_links as $key=>$value){
                    $video_data['course_content_id'] = $content->id;
                    $video_data['type'] = '1';

                    $video_data['title'] = $video_titles[$key];
                    $video_data['description'] = $video_descriptions[$key];
                    $video_data['video_spotify_link'] = $value;
                    
                    // Handle thumbnail upload for video
                    if(isset($video_thumbnails[$key]) && $video_thumbnails[$key]) {
                        $time = microtime(true);
                        $timeMilliseconds = round($time * 1000);
                        $timeString = (string) $timeMilliseconds;

                        $fileName = $timeString . '_' . $key . '.' . $video_thumbnails[$key]->extension();
                        $video_thumbnails[$key]->storeAs('course_contents/video_thumbnails', $fileName);
                        $video_data['thumbnail'] = 'storage/course_contents/video_thumbnails/' . $fileName;
                    } elseif (isset($existing_video_thumbnails[$key]) && $existing_video_thumbnails[$key]) {
                        $video_data['thumbnail'] = $existing_video_thumbnails[$key];
                    } else {
                        $video_data['thumbnail'] = null;
                    }
                    
                    $course_content_video = CourseContentLink::create($video_data);
                }
            }

            if(count($spotify_links) > 0) {
                foreach($spotify_links as $key1=>$value1){
                    $spotify_data['course_content_id'] = $content->id;
                    $spotify_data['type'] = '2';

                    $spotify_data['title'] = $spotify_titles[$key1];
                    $spotify_data['description'] = $spotify_descriptions[$key1];
                    $spotify_data['video_spotify_link'] = $value1; 
                    
                    $course_content_spotify = CourseContentLink::create($spotify_data);
                }
            }

            DB::commit();
             
            return redirect()->route('admin.course.content',['id' => $request['course_id'], 'active_tab' => $request['day']])
                            ->with('success',"Success! Course Content has been updated added.");
        }catch (Exception $e) {

            DB::rollBack();
            $message = $e->getMessage();
            return back()->withInput()->withErrors(['message' =>  $e->getMessage()]);;
        }
    }

    public function ViewCourseContentVerse($content_id) : View
    {
        $content = CourseContent::find($content_id);
        $course = Course::find($content['course_id']);
        $courseVerses = CourseDayVerse::where('course_content_id',$content_id)->get();

        return view('courses.CourseContentVerseList',compact('course','content','courseVerses'));
    }

    public function AddContentVerses($content_id) : View
    {
        $content = CourseContent::find($content_id);
        $course = Course::find($content['course_id']);
        return view('courses.AddContentVerse',compact('content','course','content_id'));
    }

    public function SaveContentVerses(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $a =  $request->validate([
                'course_content_id' => 'required',
                'testament' => 'required',
                'book' => 'required',
                'chapter' => 'required',
                'verse_from' => 'required',
                'verse_to' => 'required',
                
            ]);

            $inputData = $request->all();

            $Course_Day_Verse = CourseDayVerse::create($inputData);
            DB::commit();

            $course_content=CourseContent::find($request['course_content_id']);
             
            return redirect()->route('admin.view.course.content.verse',['content_id' => $course_content['id']])
                            ->with('success',"Success! New verse details has been successfully added.");
        }catch (Exception $e) {

            DB::rollBack();
            $message = $e->getMessage();
            return back()->withInput()->withErrors(['message' =>  $e->getMessage()]);;
        }
    }

    public function EditContentVerses($content_verse_id) : View
    {      
        $verse_date = CourseDayVerse::where('id',$content_verse_id)->first();
        $content = CourseContent::where('id',$verse_date->course_content_id)->first();
        $course = Course::where('id',$content->course_id)->first();

        $testaments = Testament::all();
        $books = Book::all();
        $chapters = Chapter::all();
        $verses = HolyStatement::select('statement_id','statement_no')->get();

        return view('courses.EditContentVerse',compact('verse_date','content','course','testaments','books','chapters',
            'verses'));
    }

    public function UpdateContentVerses(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {

            $content = CourseDayVerse::find($request->id);
            $course_content = CourseContent::where('id',$content['course_content_id'])->first();

            $a =  $request->validate([
                'testament' => 'required',
                'book' => 'required',
                'chapter' => 'required',
                'verse_from' => 'required',
                'verse_to' => 'required',
                
            ]);

            $inputData = $request->all();

            $content->update($inputData);
            DB::commit();
            return redirect()->route('admin.course.content',['id' => $course_content['course_id']])
                            ->with('success',"Success! Verse details has been updated.");
        }catch (Exception $e) {

            DB::rollBack();
            $message = $e->getMessage();
            return back()->withInput()->withErrors(['message' =>  $e->getMessage()]);;
        }
    }

    public function DeleteContentVerses(Request $request) : JsonResponse
    {
        DB::beginTransaction();
        try{
            $id = $request->id;
            $section =CourseDayVerse::where('id',$id)->first();
            $content = CourseContent::where('id',$section->course_content_id)->first();
            $course = Course::where('id',$content->course_id)->first();
            $hasPastBatch = $course->BatchDetails->contains(function ($batch) {
                return $batch->start_date <= now()->toDateString();
            });
            
            if($hasPastBatch==false){
                            
                $section->delete();
                DB::commit();
                $return['status'] = "success";  

            }else{
                $return['status'] = 'Forbidden';
            }

         }catch (Exception $e) {

            DB::rollBack();
            $return['status'] = $e->getMessage();
        }
        return response()->json($return);
    }

    public function BatchDetail($id) : View
    {   
        $batch_id = $id;
        return view('courses.BatchDetail',compact('batch_id'));

    }

    public function NewBatch($id) : View
    {
        $course = Course::find($id);
        return view('courses.NewBatch',compact('course'));

    }

    public function SaveBatch(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $a =  $request->validate([
                'course_id' => 'required',
                'batch_name' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'last_date' => 'required',
                'date_visibility' => 'required',
                'status' => 'required',
                
            ]);

            $inputData = $request->all();

            $batch = Batch::create($inputData);
            DB::commit();

            if($batch['status']=='Active'){

                $push_data = [];

                $push_data['tokens']    =  User::whereNotNull('refresh_token')
                                                ->pluck('refresh_token')
                                                ->toArray();

                $push_data['title']         =   'New Batch Date Alert: Don’t Miss Out on This Blessing!';
                $push_data['body']          =   'Enrol now in our next batch of '.$batch->course->course_name                             .' starting '.$batch->start_date.'.'.PHP_EOL.'Last date for enrolment: '.$batch->last_date.'.';

                $push_data['route']         =   'NewBatch';
                $push_data['id']            =   $batch['id'];
                $push_data['category']      =   'NewBatch';

                $push_data['data1']         =   $batch->course->course_name;
                $push_data['data2']         =   $batch->start_date;
                $push_data['data3']         =   $batch->last_date;
                $push_data['data4']         =   null;
                $push_data['data5']         =   null;
                $push_data['image1']        =   null;
                $push_data['user']        =   null;

                if (!empty($push_data['tokens'])) {
                    if(env('QUEUE_CONNECTION') === 'sync') {
                        $pusher = new NotificationPusher();
                        //$pusher->push($push_data);
                    }else{
                        SendPushNotification::dispatch($push_data)->onQueue('push-notifications');
                    }
                }

            }
            return redirect()->route('admin.course.details',['id' => $request['course_id']])
                            ->with('success',"Success! New Batch has been successfully added.");
        }catch (Exception $e) {

            DB::rollBack();
            $message = $e->getMessage();
            return back()->withInput()->withErrors(['message' =>  $e->getMessage()]);;
        }
    }

    public function EditBatch($id) : View
    {
        $batch = Batch::where('id',$id)->first();
        $course = Course::find($batch['course_id']);
        return view('courses.EditNewBatch',compact('course','batch'));

    }

    public function UpdateBatch(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {

            $batch = Batch::find($request->id);

            $old_status = $batch['status'];

            $a =  $request->validate([
                'course_id' => 'required',
                'batch_name' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'last_date' => 'required',
                'date_visibility' => 'required',
                'status' => 'required',
                
            ]);

            $inputData = $request->all();

            $batch->update($inputData);
            DB::commit();

            if($old_status=='Suspended' && $batch['status']=='Active'){

                $push_data = [];

                $push_data['tokens']    =  User::whereNotNull('refresh_token')
                                                ->pluck('refresh_token')
                                                ->toArray();

                $push_data['title']         =   'New Batch Date Alert: Don’t Miss Out on This Blessing!';
                $push_data['body']          =   'Enrol now in our next batch of '.$batch->course->course_name                             .' starting '.$batch->start_date.'.'.PHP_EOL.'Last date for enrolment: '.$batch->last_date.'.';

                $push_data['route']         =   'NewBatch';
                $push_data['id']            =   $batch['id'];
                $push_data['category']      =   'NewBatch';

                $push_data['data1']         =   $batch->course->course_name;
                $push_data['data2']         =   $batch->start_date;
                $push_data['data3']         =   $batch->last_date;
                $push_data['data4']         =   null;
                $push_data['data5']         =   null;
                $push_data['image1']        =   null;
                $push_data['user']        =   null;

                if (!empty($push_data['tokens'])) {
                    if(env('QUEUE_CONNECTION') === 'sync') {
                        $pusher = new NotificationPusher();
                        //$pusher->push($push_data);
                    }else{
                        SendPushNotification::dispatch($push_data)->onQueue('push-notifications');
                    }
                }

            }
            return redirect()->route('admin.course.details',['id' => $request['course_id']])
                            ->with('success',"Success! New Batch has been successfully updated.");
        }catch (Exception $e) {

            DB::rollBack();
            $message = $e->getMessage();
            return back()->withInput()->withErrors(['message' =>  $e->getMessage()]);;
        }
    }


    public function BatchStatusChange(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $batch = Batch::find($request['id']);
            if($batch->status=='Active'){
                $batch->status=2;
            }else{
                $batch->status=1;
            }
            $batch->save();;
            DB::commit();

            return response()->json(['success' => true ,'msg' => 'Batch status updated','status' =>$course['status']]);

        }catch (Exception $e) {

            DB::rollBack();
            $message = $e->getMessage();

            return response()->json(['success' => false,'msg' => $e->getMessage()]);
        }
    }

    public function DeleteBatch($id): RedirectResponse
    {

        DB::beginTransaction();
        try {
            $batch = Batch::find($id);
            $course_id = $batch['course_id'];
            $batch->delete();
            DB::commit();

            return redirect()->route('admin.course.details',['id' => $course_id])
                            ->with('success',"Success! Batch has been deleted successfully.");

        }catch (Exception $e) {

            DB::rollBack();
            $message = $e->getMessage();

            return redirect()->route('admin.course.details',['id' => $course_id])
                            ->with('error',$e->getMessage());
        }
    }

    public function BatchUsersDatatable(Request $request)
    {
        if(request()->ajax()) {
            return datatables()
            ->of(UserLMS::select('*')->where('batch_id',$request['batch_id']))

            ->addColumn('user_name', function ($user_lms) {

               return $user_lms->user_name;
            })
            ->addColumn('start_date', function ($user_lms) {

                return \Carbon\Carbon::createFromFormat('Y-m-d', $user_lms->start_date)->format('d-m-y');       
            })
            ->addColumn('progress', function ($user_lms) {

                return $user_lms->progress.'%';       
            })
            ->addColumn('completed_status', function ($user_lms) {

                return $user_lms->completed_status_name;       
            })
            ->addColumn('action', 'courses.batch-datatable-action')
            ->addColumn('status', 'courses.batch-datatable-status-action')
            ->rawColumns(['action','status'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('courses.BatchDetail');
    }


 
}
