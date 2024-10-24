<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SidebarController;
use App\Http\Controllers\Api\HomeController;


Route::post('login',[UserController::class, 'loginUser']);
Route::post('verify_otp',[UserController::class, 'VerifyOtp']);

Route::post('signup',[UserController::class, 'Signup']);

Route::middleware('auth:sanctum')->group(function(){

    Route::get('myprofile',[UserController::class, 'myProfile']);
    Route::post('edit_profile',[UserController::class, 'editProfile']);
    Route::get('logout',[UserController::class, 'logoutuser']);
    
    Route::get('got_questions',[SidebarController::class, 'GotQuestions']);
    Route::get('got_question_categories',[SidebarController::class, 'GotQuestionCategories']);
    Route::get('got_question_sub_categories',[SidebarController::class, 'GotQuestionSubCategories']);

    Route::get('asked_questions',[SidebarController::class, 'AskedQuestions']);
    Route::post('ask_a_question',[SidebarController::class, 'AskAQuestion']);

    Route::get('my_notes',[SidebarController::class, 'MyNotes']);
    Route::post('add_note',[SidebarController::class, 'AddNote']);

    Route::get('home', [HomeController::class, 'Home']);
    Route::get('all_courses', [HomeController::class, 'AllCourses']);
    Route::get('course_details', [HomeController::class, 'CourseDetails']);
    Route::post('enroll_batch',[HomeController::class, 'EnrollBatch']);
    Route::get('course_day_content',[HomeController::class, 'CourseDayContent']);
    Route::post('mark_as_read',[HomeController::class, 'MarkAsRead']);

    Route::get('bible_study' , [HomeController::class, 'BibleStudy']);
    Route::get('bible_study_chapters' , [HomeController::class, 'BibleStudyChapters']);
});
