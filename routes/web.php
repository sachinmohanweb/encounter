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
use App\Http\Controllers\BibleDbController;
use App\Http\Controllers\UserQNAController;

Route::get('admin', [HomeController::class, 'admin_index'])->name('index');
Route::post('/login', [UserController::class, 'admin_login'])->name('admin.login');

Route::middleware('auth:admin')->group(function(){

    Route::get('logout', [UserController::class, 'admin_logout'])->name('admin.logout');
    Route::get('dashboard', [HomeController::class, 'admin_dashboard'])->name('admin.dashboard');

    Route::get('userprofile', [UserController::class, 'UsersList'])->name('admin.user-profile');
    Route::post('usersDatatable', [UserController::class, 'admin_users_Datatable'])
                ->name('admin.users.datatable');
    Route::post('activatesuspenduser', [UserController::class, 'admin_user_status_change'])
                ->name('admin.user.status.change');


    Route::get('usernotes', [UserController::class, 'UsersNotes'])->name('admin.user.notes');
    Route::post('usernotesDatatable', [UserController::class, 'UsersNotes_Datatable'])
                ->name('admin.user.notes.datatable');
    Route::post('activatesusernotes', [UserController::class, 'UsersNotes_status_change'])
                ->name('admin.user.notes.status.change');
    
    Route::get('userlms', [UserController::class, 'UserLms'])->name('admin.user.lms');
    Route::post('userlmsDatatable', [UserController::class, 'UsersLMS_Datatable'])
                ->name('admin.user.lms.datatable');
    Route::post('activatesuserlms', [UserController::class, 'UsersLMS_status_change'])
                ->name('admin.user.lms.status.change');

    Route::get('gotquestion', [GotQuestionController::class, 'GotQuestion'])->name('admin.got-question');
    Route::post('get_gq_category_list', [GotQuestionController::class, 'gq_category_list'])->name('gqCategory.list');
    Route::post('get_gq_subcategory_list', [GotQuestionController::class, 'gq_subcategory_list'])
            ->name('gqSubcategory.list');
    Route::post('gotquestionDatatable',[GotQuestionController::class,'QotQuestionDatatable'])
            ->name('admin.gotquestion.datatable');
    Route::get('creategotquestion', [GotQuestionController::class, 'AddGotQuestion'])
            ->name('admin.add.GotQuestion');
    Route::post('storegotquestion', [GotQuestionController::class, 'StoreGotQuestion'])
            ->name('admin.store.GotQuestion');
    Route::get('editgotquestion/{id}', [GotQuestionController::class, 'EditGotQuestion'])
            ->name('admin.edit.GotQuestion');
    Route::post('updategotquestion', [GotQuestionController::class, 'UpdateGotQuestion'])
            ->name('admin.update.GotQuestion');
    Route::post('deletegotquestion', [GotQuestionController::class, 'DeleteGotQuestion'])
            ->name('admin.delete.GotQuestion');

    Route::get('gq_categories', [GotQuestionController::class, 'GQ_Categories'])->name('admin.gq.categories');
    Route::post('gotcategoriesDatatable',[GotQuestionController::class,'GQCategoriesDatatable'])
            ->name('admin.categories.datatable');
    Route::post('store_gqcategory', [GotQuestionController::class, 'StoreGQCategory'])
            ->name('admin.store.GQCategory');
    Route::post('deletegqcategory', [GotQuestionController::class, 'DeleteGQCategory'])
            ->name('admin.delete.GQCategory');

    Route::get('gq_sub_categories', [GotQuestionController::class, 'GQ_Sub_Categories'])->name('admin.gq.subcategories');
    Route::post('gotsubcategoriesDatatable',[GotQuestionController::class,'GQSubCategoriesDatatable'])
            ->name('admin.subcategories.datatable');
    Route::post('store_gqsubcategory', [GotQuestionController::class, 'StoreGQSubCategory'])
            ->name('admin.store.GQSubCategory');
    Route::post('deletegqsubcategory', [GotQuestionController::class, 'DeleteGQSubCategory'])
            ->name('admin.delete.GQSubCategory');

    Route::get('user_qna', [UserQNAController::class, 'UserQNAList'])->name('admin.user_qna');
    Route::get('user_qna_details/{id}', [UserQNAController::class,'UserQNADetails'])->name('admin.user_qna.details');
    Route::get('get_user_qna/{id}', [UserQNAController::class, 'UserQNADetailsModal'])
            ->name('admin.user_qna.details.modal');
    Route::post('update_user_qna_answer', [UserQNAController::class, 'UpdateUserQNAAnswer'])
            ->name('admin.update.user_qna.answer');

    Route::get('dailybibleverse', [BibleVerseController::class, 'DailyBibleVerse'])
            ->name('admin.daily.bible.verse');
    Route::post('dialybibleverseDatatable',[BibleVerseController::class,'BibleVerseDatatable'])
            ->name('admin.bible_verse.datatable');
    Route::get('adddailybibleverse', [BibleVerseController::class, 'AddDailyBibleVerse'])
            ->name('admin.add.daily.bible.verse');
    Route::post('storedailybibleverse', [BibleVerseController::class, 'StoreDailyBibleVerse'])
            ->name('admin.store.DailyBibleVerse');
    Route::get('editdailybibleverse/{id}', [BibleVerseController::class, 'EditDailyBibleVerse'])
            ->name('admin.edit.daily.bible.verse');
    Route::post('updatedailybibleverse', [BibleVerseController::class, 'UpdateDailyBibleVerse'])
            ->name('admin.update.DailyBibleVerse');
    Route::post('deletedailybibleverse', [BibleVerseController::class, 'DeleteDailyBibleVerse'])
            ->name('admin.delete.DailyBibleVerse');
    Route::post('statuschangebibleverse', [BibleVerseController::class, 'admin_bible_verse_status_change'])
            ->name('admin.bible.verse.status.change');
    Route::post('/get_bible_verse_theme_list', [BibleVerseController::class, 'BibleVerseTheme'])
            ->name('bible.verse.theme.list');

    Route::get('courselist', [CourseController::class, 'CourseList'])->name('admin.course.list');
    Route::get('addcourse', [CourseController::class, 'AddCourse'])->name('admin.add.course');
    Route::post('savecourse', [CourseController::class, 'SaveCourse'])->name('admin.save.course');
    Route::post('coursestatus', [CourseController::class, 'CourseStatusChange'])
                ->name('admin.course.status.change');
    Route::get('coursedetails/{id}', [CourseController::class,'CourseDetails'])->name('admin.course.details');
    Route::get('editcourse/{id}', [CourseController::class, 'EditCourse'])->name('admin.edit.course');
    Route::post('updatecourse', [CourseController::class, 'UpdateCourse'])
            ->name('admin.update.course');
    
    Route::post('/get_bible_list', [BibleDbController::class, 'bible_list'])->name('bible.list');
    Route::post('/get_testament_list', [BibleDbController::class, 'testament_list'])->name('testament.list');
    Route::post('/get_book_list', [BibleDbController::class, 'book_list'])->name('book.list');
    Route::post('/get_chapter_list',[BibleDbController::class, 'chapter_list'])->name('chapter.list');
    Route::post('/get_verse_no_list',[BibleDbController::class, 'verse_list'])->name('verse.list');
    Route::get('bibleview', [BibleDbController::class, 'BibleView'])->name('admin.bible.view');
    Route::post('bibleviewDatatable',[BibleDbController::class,'BibleVeiewDatatable'])->name('admin.bible_view.datatable');
    Route::get('bibleviewread/{chapter_id}', [BibleDbController::class, 'BibleViewRead'])->name('admin.read.bible.view.verse');
    Route::post('/getholystatement', [BibleDbController::class, 'get_holy_statement'])->name('admin.get.holy_statement');
    Route::post('updateholystatement/{id}', [BibleDbController::class, 'UpdateHolyStatement'])->name('admin.update.HolyStatement');

    Route::get('bookimageview', [BibleDbController::class, 'bookImageView'])->name('admin.book.image.view');
    Route::post('bookimageDatatable',[BibleDbController::class,'bookImageDatatable'])
                ->name('admin.bookimage_view.datatable');
    Route::post('savebookimage', [BibleDbController::class, 'SaveBookImage'])->name('admin.save.book.image');
    Route::delete('/admin/book-thumb/{id}', [BibleDbController::class, 'DeleteBookImage'])->name('admin.delete.book.thumb');


    Route::get('coursecontent/{id}', [CourseController::class, 'CourseContent'])->name('admin.course.content');
    Route::get('addcoursecontent/{course_id}/{day}', [CourseController::class, 'AddCourseContent'])
            ->name('admin.add.course.content');
    Route::post('savecoursecontent', [CourseController::class, 'SaveCourseContent'])
            ->name('admin.save.course.content');
    Route::get('editcoursecontent/{content_id}', [CourseController::class, 'EditCourseContent'])
            ->name('admin.edit.course.content');
    Route::post('updatecoursecontent', [CourseController::class, 'UpdateCourseContent'])
            ->name('admin.update.course.content');

    Route::get('viewcoursecontentverse/{content_id}', [CourseController::class, 'ViewCourseContentVerse'])
            ->name('admin.view.course.content.verse');
    Route::get('addcontentverses/{content_id}', [CourseController::class, 'AddContentVerses'])
            ->name('admin.add.content.verses');
    Route::post('savecontentverses', [CourseController::class, 'SaveContentVerses'])
            ->name('admin.save.content.verses');
    Route::get('editcontentverses/{verse_id}', [CourseController::class, 'EditContentVerses'])
            ->name('admin.edit.content.verses');
    Route::post('updatecontentverses', [CourseController::class, 'UpdateContentVerses'])
            ->name('admin.update.content.verses');

    Route::get('batchdetail/{id}', [CourseController::class, 'BatchDetail'])->name('admin.batch.detail');
    Route::post('batchusersDatatable',[CourseController::class,'BatchUsersDatatable'])->name('admin.batch.users.datatable');
    Route::get('newbatch/{id}', [CourseController::class, 'NewBatch'])->name('admin.new.batch');
    Route::post('savebatch', [CourseController::class, 'SaveBatch'])->name('admin.save.batch');
    Route::get('editbatch/{id}', [CourseController::class, 'EditBatch'])->name('admin.edit.batch');
    Route::post('updatebatch', [CourseController::class, 'UpdateBatch'])->name('admin.update.batch');
    Route::post('batchstatus', [CourseController::class, 'BatchStatusChange'])
                ->name('admin.batch.status.change');
    Route::get('deletebatch/{id}', [CourseController::class,'DeleteBatch'])
                ->name('admin.delete.batch');

    Route::get('notifications', [NotificationController::class, 'Notifications'])
            ->name('admin.notification.list');
    Route::post('notificationsDatatable',[NotificationController::class,'NotificationsDatatable'])
            ->name('admin.notification.datatable');
    Route::get('createnotification', [NotificationController::class, 'AddNotification'])
            ->name('admin.add.notification');
    Route::post('storenotification', [NotificationController::class, 'StoreNotification'])
            ->name('admin.store.notification');
    Route::get('editnotification/{id}', [NotificationController::class, 'EditNotification'])
            ->name('admin.edit.notification');
    Route::post('updatenotification', [NotificationController::class, 'UpdateNotification'])
            ->name('admin.update.notification');
    Route::post('deletenotification', [NotificationController::class, 'DeleteNotification'])
            ->name('admin.delete.notification');


    Route::get('gereralreference', [GenReferenceController::class, 'GereralReference'])
            ->name('admin.gereralreference');
    Route::get('addgereralreference', [GenReferenceController::class, 'AddGereralReference'])
            ->name('admin.add.gereralreference');
    Route::get('editgereralreference', [GenReferenceController::class, 'EditGereralReference'])
            ->name('admin.edit.gereralreference');


});

Route::get('/clear-cache', function () {
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return "Cache is cleared";
})->name('clear.cache');

