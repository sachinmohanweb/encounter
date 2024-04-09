<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GotQuestionController;
use App\Http\Controllers\BibleVerseController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\GenReferenceController;

Route::get('admin', [HomeController::class, 'admin_index'])->name('index');
Route::post('/login', [UserController::class, 'admin_login'])->name('admin.login');

Route::middleware('auth:admin')->group(function(){

    Route::get('logout', [UserController::class, 'admin_logout'])->name('admin.logout');
    Route::get('dashboard', [HomeController::class, 'admin_dashboard'])->name('admin.dashboard');

    Route::get('userprofile', [UserController::class, 'UsersList'])->name('admin.user-profile');

    Route::get('gotquestion', [GotQuestionController::class, 'GotQuestion'])->name('admin.got-question');
    Route::get('gotquestionanswer', [GotQuestionController::class, 'GotQuestionAnswer'])
            ->name('admin.gotquestionanswer');

    Route::get('dailybibleverse', [BibleVerseController::class, 'DailyBibleVerse'])
            ->name('admin.daily.bible.verse');
    Route::get('adddailybibleverse', [BibleVerseController::class, 'AddDailyBibleVerse'])
            ->name('admin.add.daily.bible.verse');
    Route::get('editdailybibleverse', [BibleVerseController::class, 'EditDailyBibleVerse'])
            ->name('admin.edit.daily.bible.verse');

    Route::get('courselist', [CourseController::class, 'CourseList'])->name('admin.course.list');
    Route::get('addcourse', [CourseController::class, 'AddCourse'])->name('admin.add.course');
    Route::get('editcourse', [CourseController::class, 'EditCourse'])->name('admin.edit.course');
    Route::get('coursedetails', [CourseController::class, 'CourseDetails'])->name('admin.course.details');
    
    Route::get('coursecontent', [CourseController::class, 'CourseContent'])->name('admin.course.content');
    Route::get('addcoursecontent', [CourseController::class, 'AddCourseContent'])
            ->name('admin.add.course.content');
    Route::get('editcoursecontent', [CourseController::class, 'EditCourseContent'])
            ->name('admin.edit.course.content');

    Route::get('batchdetail', [CourseController::class, 'BatchDetail'])->name('admin.batch.detail');
    Route::get('newbatch', [CourseController::class, 'NewBatch'])->name('admin.new.batch');
    Route::get('editbatch', [CourseController::class, 'EditBatch'])->name('admin.edit.batch');

    Route::get('notifications', [NotificationController::class, 'Notifications'])
            ->name('admin.notification.list');
    Route::get('notification', [NotificationController::class, 'AddNotification'])
            ->name('admin.add.notification');
    Route::get('editnotification', [NotificationController::class, 'EditNotification'])
            ->name('admin.edit.notification');

    Route::get('gereralreference', [GenReferenceController::class, 'GereralReference'])
            ->name('admin.gereralreference');
    Route::get('addgereralreference', [GenReferenceController::class, 'AddGereralReference'])
            ->name('admin.add.gereralreference');
    Route::get('editgereralreference', [GenReferenceController::class, 'EditGereralReference'])
            ->name('admin.edit.gereralreference');

    Route::get('userlms', [UserController::class, 'UserLms'])->name('admin.user.lms');

});

Route::get('/clear-cache', function () {
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return "Cache is cleared";
})->name('clear.cache');

