<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CustomerDetailController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SalatRecitationController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\TagDetailController;
use App\Http\Controllers\Admin\IslamicSongsController;
use App\Http\Controllers\Admin\HajjCheckListController;
use App\Http\Controllers\Admin\HajjSubListController;
use App\Http\Controllers\Admin\ReciteLanguageController;
use App\Http\Controllers\Admin\SurahRecitationController;
use App\Http\Controllers\Admin\RecitationFileController;
use App\Http\Controllers\Admin\QuranProgramCategoryController;
use App\Http\Controllers\Admin\QuranProgramController;
use App\Http\Controllers\Admin\QuranProgramFileController;
use App\Http\Controllers\Admin\HamdElsabagController;
use App\Http\Controllers\Admin\DuaElsabagController;
use App\Http\Controllers\Admin\QuranSlowRecitationController;
use App\Http\Controllers\Admin\QuranRecitationController;
use App\Http\Controllers\Admin\TarawihPrayerRecitationController;
use App\Http\Controllers\Admin\RegularPrayerRecitationController;
use App\Http\Controllers\Admin\KhudbahLectureRecitationController;
use App\Http\Controllers\Admin\KhatiraLectureRecitationController;
use App\Http\Controllers\Admin\TahajjudPrayerListController;

use App\Http\Controllers\Admin\QuranRecitationListController;
use App\Http\Controllers\Admin\TafsirulQuranListController;
use App\Http\Controllers\Admin\KhutbahListController;
use App\Http\Controllers\Admin\AkidahController;
use App\Http\Controllers\Admin\DarsulHadithController;

use App\Http\Controllers\Admin\ArabicGrammarController;
use App\Http\Controllers\Admin\ArabicGrammerCategoryListController;

use App\Http\Controllers\Admin\YasirLectureController;
use App\Http\Controllers\Admin\YasirLectureCategoryController;
use App\Http\Controllers\Admin\JannahandJahannamController;
use App\Http\Controllers\Admin\JannahandJahannamCategoryController;


use App\Http\Controllers\Admin\DuaListController;
use App\Http\Controllers\Admin\ramadanSeriesController;
use App\Http\Controllers\Admin\StoriesController;
use App\Http\Controllers\Admin\MessageOfQuransController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return to_route('login');
});
Route::get('index', function () {
    return view('index');
});
Route::get('privacy-policy', function () {
    return view('privacy');
});


//sheikh ramadan
Route::prefix('admin')->name('admin.')->middleware(['web', 'auth'])->group(function () {
    //
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('home');
    //users & roles
    Route::get('users/get-data', [UserController::class, 'getData']);
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);

    //    customer-detail
    Route::get('customer-detail/get-data', [CustomerDetailController::class, 'getData']);
    Route::resource('customer-detail', CustomerDetailController::class);
    Route::get('customer-detail-status/{status}', [CustomerDetailController::class, 'statusChange'])->name('customer-detail.status');
    Route::get('customer-detail-device/{id}', [CustomerDetailController::class, 'resetDevice'])->name('customer-detail.device');

    Route::get('quran_recitations/get-data', [QuranRecitationController::class, 'getData']);
    Route::resource('quran_recitations', QuranRecitationController::class);
    Route::get('quran_recitations-status/{status}', [QuranRecitationController::class, 'statusChange'])->name('quran_recitations.status');
    Route::get('quran_recitations_order_view', [QuranRecitationController::class, 'orderView'])->name('quran_recitations.order.view');
    Route::post('quran_recitations_order_view/reorder', [QuranRecitationController::class, 'reOrderList'])->name('quran_recitations.reorder');
    Route::post('quran_recitations/upload-video-file', [QuranRecitationController::class, 'uploadRecitationVideo'])->name('quran_recitations.upload.video');
    Route::post('quran_recitations/upload-audio-file', [QuranRecitationController::class, 'uploadRecitationAudio'])->name('quran_recitations.upload.audio');


    Route::get('quran_slow_recitations/get-data', [QuranSlowRecitationController::class, 'getData']);
    Route::resource('quran_slow_recitations', QuranSlowRecitationController::class);
    Route::get('quran_slow_recitations-status/{status}', [QuranSlowRecitationController::class, 'statusChange'])->name('quran_slow_recitations.status');
    Route::get('quran_slow_recitations_order_view', [QuranSlowRecitationController::class, 'orderView'])->name('quran_slow_recitations.order.view');
    Route::post('quran_slow_recitations_order_view/reorder', [QuranSlowRecitationController::class, 'reOrderList'])->name('quran_slow_recitations.reorder');
    Route::post('quran_slow_recitations/upload-video-file', [QuranSlowRecitationController::class, 'uploadRecitationVideo'])->name('quran_slow_recitations.upload.video');
    Route::post('quran_slow_recitations/upload-audio-file', [QuranSlowRecitationController::class, 'uploadRecitationAudio'])->name('quran_slow_recitations.upload.audio');

    Route::get('tarawih_prayer_recitations/get-data', [TarawihPrayerRecitationController::class, 'getData']);
    Route::resource('tarawih_prayer_recitations', TarawihPrayerRecitationController::class);
    Route::get('tarawih_prayer_recitations-status/{status}', [TarawihPrayerRecitationController::class, 'statusChange'])->name('tarawih_prayer_recitations.status');
    Route::get('tarawih_prayer_recitations_order_view', [TarawihPrayerRecitationController::class, 'orderView'])->name('tarawih_prayer_recitations.order.view');
    Route::post('tarawih_prayer_recitations_order_view/reorder', [TarawihPrayerRecitationController::class, 'reOrderList'])->name('tarawih_prayer_recitations.reorder');
    Route::post('tarawih_prayer_recitations/upload-video-file', [TarawihPrayerRecitationController::class, 'uploadRecitationVideo'])->name('tarawih_prayer_recitations.upload.video');
    Route::post('tarawih_prayer_recitations/upload-audio-file', [TarawihPrayerRecitationController::class, 'uploadRecitationAudio'])->name('tarawih_prayer_recitations.upload.audio');

    Route::get('regular_prayer_recitations/get-data', [RegularPrayerRecitationController::class, 'getData']);
    Route::resource('regular_prayer_recitations', RegularPrayerRecitationController::class);
    Route::get('regular_prayer_recitations-status/{status}', [RegularPrayerRecitationController::class, 'statusChange'])->name('regular_prayer_recitations.status');
    Route::get('regular_prayer_recitations_order_view', [RegularPrayerRecitationController::class, 'orderView'])->name('regular_prayer_recitations.order.view');
    Route::post('regular_prayer_recitations_order_view/reorder', [RegularPrayerRecitationController::class, 'reOrderList'])->name('regular_prayer_recitations.reorder');
    Route::post('regular_prayer_recitations/upload-video-file', [RegularPrayerRecitationController::class, 'uploadRecitationVideo'])->name('regular_prayer_recitations.upload.video');
    Route::post('regular_prayer_recitations/upload-audio-file', [RegularPrayerRecitationController::class, 'uploadRecitationAudio'])->name('regular_prayer_recitations.upload.audio');

    Route::get('khudbah_lecture_recitations/get-data', [KhudbahLectureRecitationController::class, 'getData']);
    Route::resource('khudbah_lecture_recitations', KhudbahLectureRecitationController::class);
    Route::get('khudbah_lecture_recitations-status/{status}', [KhudbahLectureRecitationController::class, 'statusChange'])->name('khudbah_lecture_recitations.status');
    Route::get('khudbah_lecture_recitations_order_view', [KhudbahLectureRecitationController::class, 'orderView'])->name('khudbah_lecture_recitations.order.view');
    Route::post('khudbah_lecture_recitations_order_view/reorder', [KhudbahLectureRecitationController::class, 'reOrderList'])->name('khudbah_lecture_recitations.reorder');
    Route::post('khudbah_lecture_recitations/upload-video-file', [KhudbahLectureRecitationController::class, 'uploadRecitationVideo'])->name('khudbah_lecture_recitations.upload.video');
    Route::post('khudbah_lecture_recitations/upload-audio-file', [KhudbahLectureRecitationController::class, 'uploadRecitationAudio'])->name('khudbah_lecture_recitations.upload.audio');

    Route::get('khatira_lecture_recitations/get-data', [KhatiraLectureRecitationController::class, 'getData']);
    Route::resource('khatira_lecture_recitations', KhatiraLectureRecitationController::class);
    Route::get('khatira_lecture_recitations-status/{status}', [KhatiraLectureRecitationController::class, 'statusChange'])->name('khatira_lecture_recitations.status');
    Route::get('khatira_lecture_recitations_order_view', [KhatiraLectureRecitationController::class, 'orderView'])->name('khatira_lecture_recitations.order.view');
    Route::post('khatira_lecture_recitations_order_view/reorder', [KhatiraLectureRecitationController::class, 'reOrderList'])->name('khatira_lecture_recitations.reorder');
    Route::post('khatira_lecture_recitations/upload-video-file', [KhatiraLectureRecitationController::class, 'uploadRecitationVideo'])->name('khatira_lecture_recitations.upload.video');
    Route::post('khatira_lecture_recitations/upload-audio-file', [KhatiraLectureRecitationController::class, 'uploadRecitationAudio'])->name('khatira_lecture_recitations.audio');

    Route::get('dua_elsabags/get-data', [DuaElsabagController::class, 'getData']);
    Route::resource('dua_elsabags', DuaElsabagController::class);
    Route::get('dua_elsabags-status/{status}', [DuaElsabagController::class, 'statusChange'])->name('dua_elsabags.status');
    Route::get('dua_elsabags_order_view', [DuaElsabagController::class, 'orderView'])->name('dua_elsabags.order.view');
    Route::post('dua_elsabags_order_view/reorder', [DuaElsabagController::class, 'reOrderList'])->name('dua_elsabags.reorder');
    Route::post('dua_elsabags/upload-video-file', [DuaElsabagController::class, 'uploadRecitationVideo'])->name('dua_elsabags.upload.video');
    Route::post('dua_elsabags/upload-audio-file', [DuaElsabagController::class, 'uploadRecitationAudio'])->name('dua_elsabags.upload.audio');

    Route::get('hamd_elsabags/get-data', [HamdElsabagController::class, 'getData']);
    Route::resource('hamd_elsabags', HamdElsabagController::class);
    Route::get('hamd_elsabags-status/{status}', [HamdElsabagController::class, 'statusChange'])->name('hamd_elsabags.status');
    Route::get('hamd_elsabags_order_view', [HamdElsabagController::class, 'orderView'])->name('hamd_elsabags.order.view');
    Route::post('hamd_elsabags_order_view/reorder', [HamdElsabagController::class, 'reOrderList'])->name('hamd_elsabags.reorder');
    Route::post('hamd_elsabags/upload-video-file', [HamdElsabagController::class, 'uploadRecitationVideo'])->name('hamd_elsabags.upload.video');
    Route::post('hamd_elsabags/upload-audio-file', [HamdElsabagController::class, 'uploadRecitationAudio'])->name('hamd_elsabags.upload.audio');

    Route::get('tahajjud_prayer/get-data', [TahajjudPrayerListController::class, 'getData']);
    Route::resource('tahajjud_prayer', TahajjudPrayerListController::class);
    Route::get('tahajjud_prayer-status/{status}', [TahajjudPrayerListController::class, 'statusChange'])->name('tahajjud_prayer.status');
    Route::post('tahajjud_prayer/upload-video-file', [TahajjudPrayerListController::class, 'uploadRecitationVideo'])->name('tahajjud_prayer.upload.video');
    Route::post('tahajjud_prayer/upload-audio-file', [TahajjudPrayerListController::class, 'uploadRecitationAudio'])->name('tahajjud_prayer.upload.audio');


    Route::get('quran-recitation-list/get-data', [QuranRecitationListController::class, 'getData']);
    Route::resource('quran-recitation-list', QuranRecitationListController::class);
    Route::get('quran-recitation-list-status/{status}', [QuranRecitationListController::class, 'statusChange'])->name('quran-recitation-list.status');
    Route::post('quran-recitation-list/upload-video-file', [QuranRecitationListController::class, 'uploadRecitationVideo'])->name('quran-recitation-list.upload.video');
    Route::post('quran-recitation-list/upload-audio-file', [QuranRecitationListController::class, 'uploadRecitationAudio'])->name('quran-recitation-list.upload.audio');

    Route::get('tafsirul-quran-list/get-data', [TafsirulQuranListController::class, 'getData']);
    Route::resource('tafsirul-quran-list', TafsirulQuranListController::class);
    Route::get('tafsirul-quran-list-status/{status}', [TafsirulQuranListController::class, 'statusChange'])->name('tafsirul-quran-list.status');
    Route::post('tafsirul-quran-list/upload-video-file', [TafsirulQuranListController::class, 'uploadRecitationVideo'])->name('tafsirul-quran-list.upload.video');
    Route::post('tafsirul-quran-list/upload-audio-file', [TafsirulQuranListController::class, 'uploadRecitationAudio'])->name('tafsirul-quran-list.upload.audio');

    Route::get('khutbah_list/get-data', [KhutbahListController::class, 'getData']);
    Route::resource('khutbah_list', KhutbahListController::class);
    Route::get('khutbah_list/{status}', [KhutbahListController::class, 'statusChange'])->name('khutbah_list.status');
    Route::post('khutbah_list/upload-video-file', [KhutbahListController::class, 'uploadRecitationVideo'])->name('khutbah_list.upload.video');
    Route::post('khutbah_list/upload-audio-file', [KhutbahListController::class, 'uploadRecitationAudio'])->name('khutbah_list.upload.audio');

    Route::get('Akidah_list/get-data', [AkidahController::class, 'getData']);
    Route::resource('Akidah_list', AkidahController::class);
    Route::get('Akidah_list/{status}', [AkidahController::class, 'statusChange'])->name('Akidah_list.status');
    Route::post('Akidah_list/upload-video-file', [AkidahController::class, 'uploadRecitationVideo'])->name('Akidah_list.upload.video');
    Route::post('Akidah_list/upload-audio-file', [AkidahController::class, 'uploadRecitationAudio'])->name('Akidah_list.upload.audio');

    Route::get('DarsulHadith_list/get-data', [DarsulHadithController::class, 'getData']);
    Route::resource('DarsulHadith_list', DarsulHadithController::class);
    Route::get('DarsulHadith_list/{status}', [DarsulHadithController::class, 'statusChange'])->name('DarsulHadith_list.status');
    Route::post('DarsulHadith_list/upload-video-file', [DarsulHadithController::class, 'uploadRecitationVideo'])->name('DarsulHadith_list.upload.video');
    Route::post('DarsulHadith_list/upload-audio-file', [DarsulHadithController::class, 'uploadRecitationAudio'])->name('DarsulHadith_list.upload.audio');

    Route::get('arabic-grammar/get-data', [ArabicGrammarController::class, 'getData']);
    Route::resource('arabic-grammar', ArabicGrammarController::class);

    Route::get('arabic-grammer-category-list/get-data', [ArabicGrammerCategoryListController::class, 'getData']);
    Route::resource('arabic-grammer-category-list', ArabicGrammerCategoryListController::class);
    Route::get('arabic-grammer-category-list/{status}', [ArabicGrammerCategoryListController::class, 'statusChange'])->name('arabic-grammer-category-list.status');
    Route::post('arabic-grammer-category-list/upload-video-file', [ArabicGrammerCategoryListController::class, 'uploadRecitationVideo'])->name('arabic-grammer-category-list.upload.video');
    Route::post('arabic-grammer-category-list/upload-audio-file', [ArabicGrammerCategoryListController::class, 'uploadRecitationAudio'])->name('arabic-grammer-category-list.upload.audio');

    Route::get('yasir-lecture/get-data', [YasirLectureController::class, 'getData']);
    Route::resource('yasir-lecture', YasirLectureController::class);

    Route::get('yasir-lecture-category-list/get-data', [YasirLectureCategoryController::class, 'getData']);
    Route::resource('yasir-lecture-category-list', YasirLectureCategoryController::class);
    Route::get('yasir-lecture-category-list/{status}', [YasirLectureCategoryController::class, 'statusChange'])->name('yasir-lecture-category-list.status');
    Route::post('yasir-lecture-category-list/upload-video-file', [YasirLectureCategoryController::class, 'uploadRecitationVideo'])->name('yasir-lecture-category-list.upload.video');
    Route::post('yasir-lecture-category-list/upload-audio-file', [YasirLectureCategoryController::class, 'uploadRecitationAudio'])->name('yasir-lecture-category-list.upload.audio');

    Route::get('jannah-jahannam/get-data', [JannahandJahannamController::class, 'getData']);
    Route::resource('jannah-jahannam', JannahandJahannamController::class);

    Route::get('jannah-jahannam-category-list/get-data', [JannahandJahannamCategoryController::class, 'getData']);
    Route::resource('jannah-jahannam-category-list', JannahandJahannamCategoryController::class);
    Route::get('jannah-jahannam-category-list/{status}', [JannahandJahannamCategoryController::class, 'statusChange'])->name('jannah-jahannam-category-list.status');
    Route::post('jannah-jahannam-category-list/upload-video-file', [JannahandJahannamCategoryController::class, 'uploadRecitationVideo'])->name('jannah-jahannam-category-list.upload.video');
    Route::post('jannah-jahannam-category-list/upload-audio-file', [JannahandJahannamCategoryController::class, 'uploadRecitationAudio'])->name('jannah-jahannam-category-list.upload.audio');


    Route::get('dua_list/get-data', [DuaListController::class, 'getData']);
    Route::resource('dua_list', DuaListController::class);
    Route::get('dua_list/{status}', [DuaListController::class, 'statusChange'])->name('dua_list.status');
    Route::post('dua_list/upload-video-file', [DuaListController::class, 'uploadRecitationVideo'])->name('dua_list.upload.video');
    Route::post('dua_list/upload-audio-file', [DuaListController::class, 'uploadRecitationAudio'])->name('dua_list.upload.audio');

    Route::get('ramadan_series/get-data', [ramadanSeriesController::class, 'getData']);
    Route::resource('ramadan_series', ramadanSeriesController::class);
    Route::get('ramadan_series/{status}', [ramadanSeriesController::class, 'statusChange'])->name('ramadan_series.status');
    Route::post('ramadan_series/upload-video-file', [ramadanSeriesController::class, 'uploadRecitationVideo'])->name('ramadan_series.upload.video');
    Route::post('ramadan_series/upload-audio-file', [ramadanSeriesController::class, 'uploadRecitationAudio'])->name('ramadan_series.upload.audio');

    Route::get('stories/get-data', [StoriesController::class, 'getData']);
    Route::resource('stories', StoriesController::class);
    Route::get('stories/{status}', [StoriesController::class, 'statusChange'])->name('stories.status');
    Route::post('stories/upload-video-file', [StoriesController::class, 'uploadRecitationVideo'])->name('stories.upload.video');
    Route::post('stories/upload-audio-file', [StoriesController::class, 'uploadRecitationAudio'])->name('stories.upload.audio');

    Route::get('message_of_qurans/get-data', [MessageOfQuransController::class, 'getData']);
    Route::resource('message_of_qurans', MessageOfQuransController::class);
    Route::get('message_of_qurans/{status}', [MessageOfQuransController::class, 'statusChange'])->name('message_of_qurans.status');
    Route::post('message_of_qurans/upload-video-file', [MessageOfQuransController::class, 'uploadRecitationVideo'])->name('message_of_qurans.upload.video');
    Route::post('message_of_qurans/upload-audio-file', [MessageOfQuransController::class, 'uploadRecitationAudio'])->name('message_of_qurans.upload.audio');

});

Auth::routes(['register' => false]);



//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
