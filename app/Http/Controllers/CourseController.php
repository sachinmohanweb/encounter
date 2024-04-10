<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    
    public function CourseList() : View
    {
        return view('courses.Courselist',[]);
    }

    public function AddCourse() : View
    {
        return view('courses.AddCourse',[]);
    }

    public function CourseDetails() : View
    {
        return view('courses.CourseDetail',[]);

    }

    public function EditCourse() : View
    {
        return view('courses.EditCourse',[]);

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
