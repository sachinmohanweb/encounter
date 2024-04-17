<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

use DB;
use Session;
use Exception;

use App\Models\Course;

class CourseController extends Controller
{
    
    public function CourseList() : View
    {
        $courses = Course::all();
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
                'course_name' => 'required',
                'course_creator' => 'required',
                'no_of_days' => 'required',
                'status' => 'required',
                
            ]);

            $inputData = $request->all();

            if($request['thumbnail']){

                $fileName =str_replace(' ', '_',$request->course_name).'.'.$request['thumbnail']->extension();
                $request->thumbnail->storeAs('courses', $fileName);
                $inputData['thumbnail'] = 'storage/courses/'.$fileName;
            }

            $course = Course::create($inputData);
            DB::commit();
             
            return redirect()->route('admin.course.details',['course_id' => $course['id']])
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

        return view('courses.CourseDetail',compact('course'));

    }

    public function EditCourse($id) : View
    {
        $course = Course::where('id',$id)->first();
        return view('courses.EditCourse',compact('course'));

    }

    public function UpdateCourse(Request $request): RedirectResponse
    {
        DB::beginTransaction();
        try {

            $course = Course::find($request->id);

            $a =  $request->validate([
                'course_name' => 'required',
                'course_creator' => 'required',
                'no_of_days' => 'required',
                'status' => 'required',
                
            ]);

            $inputData = $request->all();

            if($request['thumbnail']){

                $fileName =str_replace(' ', '_',$request->course_name).'.'.$request['thumbnail']->extension();
                $request->thumbnail->storeAs('courses', $fileName);
                $inputData['thumbnail'] = 'storage/courses/'.$fileName;
            }

            $course->update($inputData);
            DB::commit();
             
            return redirect()->route('admin.course.details',['course_id' => $course['id']])
                            ->with('success',"Success! Course has been successfully updated.");
        }catch (Exception $e) {

            DB::rollBack();
            $message = $e->getMessage();
            return back()->withInput()->withErrors(['message' =>  $e->getMessage()]);;
        }
    }

    public function CourseContent() : View
    {
        return view('courses.CourseContent',[]);

    }

    public function AddCourseContent() : View
    {
        return view('courses.AddCourseContent',[]);

    }

    public function EditCourseContent() : View
    {
        return view('courses.EditCourseContent',[]);

    }

    public function BatchDetail() : View
    {
        return view('courses.BatchDetail',[]);

    }

    public function NewBatch() : View
    {
        return view('courses.NewBatch',[]);

    }

    public function EditBatch() : View
    {
        return view('courses.EditNewBatch',[]);

    }
 
}
