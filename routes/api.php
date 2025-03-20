<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SidebarController;
use App\Http\Controllers\Api\HomeController;


Route::post('login',[UserController::class, 'loginUser']);
Route::post('verify_otp',[UserController::class, 'VerifyOtp']);

Route::post('signup',[UserController::class, 'Signup']);

//Route::get('web/home', [HomeController::class, 'Home']);
Route::get('home', [HomeController::class, 'Home']);
Route::get('web/course_details', [HomeController::class, 'WebCourseDetails']);

Route::get('web/search',[UserController::class, 'WebSearchResults']);

Route::get('web/bible_study' , [HomeController::class, 'WebBibleStudy']);
Route::get('web/bible_study_chapters' , [HomeController::class, 'WebBibleStudyChapters']);

Route::get('web/notifications' , [HomeController::class, 'WebNotifications']);


Route::get('got_questions',[SidebarController::class, 'GotQuestions']);
Route::get('got_question_categories',[SidebarController::class, 'GotQuestionCategories']);
Route::get('got_question_sub_categories',[SidebarController::class, 'GotQuestionSubCategories']);

Route::middleware('auth:sanctum')->group(function(){

    Route::post('update_token',[UserController::class, 'updateToken']);
    Route::get('myprofile',[UserController::class, 'myProfile']);
    Route::post('edit_profile',[UserController::class, 'editProfile']);
    Route::get('logout',[UserController::class, 'logoutuser']);
    Route::get('delete_account',[UserController::class, 'DeleteAccount']);
    
    Route::get('search',[UserController::class, 'SearchResults']);
    //Route::post('v2/search',[UserController::class, 'SearchResultsTwo']);

    Route::get('completed_courses', [HomeController::class, 'CompletedCourses']);

    Route::get('asked_questions',[SidebarController::class, 'AskedQuestions']);
    Route::post('ask_a_question',[SidebarController::class, 'AskAQuestion']);

    Route::get('custom_notes',[SidebarController::class, 'CustomNotes']);
    Route::post('add_custom_note',[SidebarController::class, 'AddCustomNote']);

    Route::get('my_tags',[SidebarController::class, 'MyTags']);
    Route::post('add_tag',[SidebarController::class, 'AddTag']);
    Route::delete('delete_tag',[SidebarController::class, 'DeleteTag']);

    //Route::get('my_bible_markings',[SidebarController::class, 'MyBibleMarkings']);
    Route::get('v2/my_bible_markings',[SidebarController::class, 'MyBibleMarkingsV2']);
    Route::post('add_bible_marking',[SidebarController::class, 'AddBibleMarking']);
    Route::delete('delete_bible_marking',[SidebarController::class, 'DeleteBibleMarking']);

    //Route::get('home', [HomeController::class, 'Home']);
    Route::get('course_details', [HomeController::class, 'CourseDetails']);
    Route::post('enroll_batch',[HomeController::class, 'EnrollBatch']);
    Route::get('course_day_content',[HomeController::class, 'CourseDayContent']);
    Route::post('mark_as_read',[HomeController::class, 'MarkAsRead']);

    // Route::get('bible_study' , [HomeController::class, 'BibleStudy']);
    Route::get('v2/bible_study' , [HomeController::class, 'BibleStudyV2']);
    Route::get('bible_study_chapters' , [HomeController::class, 'BibleStudyChapters']);

    Route::get('notifications' , [HomeController::class, 'Notifications']);
    Route::post('clear/notification', [HomeController::class, 'clearNotification']);
    Route::post('clear_all/notifications', [HomeController::class, 'clearAllNotifications']);
    Route::post('notification_cache_clean', [HomeController::class, 'NotificationCacheClean']);
    Route::post('daily_verse_cache_clean', [HomeController::class, 'DailyVerseCCacheClean']);


    Route::get('app_banners', [HomeController::class, 'AppBanners']);

    Route::get('testapi' , [HomeController::class, 'TestApi']);
});
