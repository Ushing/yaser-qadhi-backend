<?php

use App\Http\Controllers\Api\V1\HajjController;
use App\Http\Controllers\Api\V1\HajjProcessController;
use App\Http\Controllers\Api\V1\HajjStatusController;
use App\Http\Controllers\Api\V1\QuranController;
use App\Http\Controllers\Api\V1\TagController;
use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\Api\V1\BannerController;
use App\Http\Controllers\Api\V1\DuaCategoryController;
use App\Http\Controllers\Api\V1\SalatRecitationController;
use App\Http\Controllers\Api\V1\DuaController;
use App\Http\Controllers\Api\V1\DuaSubCategoryController;
use App\Http\Controllers\Api\V1\LectureCategoryController;
use App\Http\Controllers\Api\V1\LectureController;
use App\Http\Controllers\Api\V1\IslamicSongsController;
use App\Http\Controllers\Api\V1\ResetPasswordController;
use App\Http\Controllers\Api\V1\LectureSubCategoryController;
use App\Http\Controllers\Api\V1\QuranProfileController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CouponController;
use App\Http\Controllers\Api\V1\DonationController;
use App\Http\Controllers\Api\V1\QuranBookmarkController;
use App\Http\Controllers\Api\V1\SubscriptioDetailnController;
use App\Http\Controllers\Api\V1\SubscriptionDetailController;
use App\Http\Controllers\Api\V1\SurahRecitationController;
use App\Http\Controllers\Api\V1\QuranProgramController;
use App\Http\Controllers\Api\V1\QuranStatusController;
use App\Http\Controllers\Api\V1\QuranPlanController;
use App\Http\Controllers\Api\V1\RecitationController;
use App\Http\Controllers\Api\V1\TahajjudPrayerListController;


use App\Http\Controllers\Api\V1\QuranRecitationController;
use App\Http\Controllers\Api\V1\TafsirulQuranListController;

use App\Http\Controllers\Api\V1\AkidahControllerController;
use App\Http\Controllers\Api\V1\DarsulHadithController;

use App\Http\Controllers\Api\V1\ArabicGrammarController;
use App\Http\Controllers\Api\V1\ArabicGrammarCategoryListController;

use App\Http\Controllers\Api\V1\YasirLectureController;
use App\Http\Controllers\Api\V1\YasirLectureCategoryController;
use App\Http\Controllers\Api\V1\JannahJahannamController;
use App\Http\Controllers\Api\V1\JannahJahannamCategoryController;

use App\Http\Controllers\Api\V1\DuaListController;
use App\Http\Controllers\Api\V1\MessageOfQuranController;
use App\Http\Controllers\Api\V1\RamandanSeriesController;
use App\Http\Controllers\Api\V1\StoriesController;
use App\Models\Coupon;
use App\Models\CustomerDetail;
use App\Models\QuranBookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

});

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('auth/logout', [AuthController::class, 'logout']);

});

Route::post('auth/register',[AuthController::class, 'register']);
Route::post('auth/resend-otp-to-mail',[AuthController::class, 'resendOtpForVerification']);
Route::post('auth/register/verify', [AuthController::class, 'verifyEmailForRegistration'])->name('customer.verify');
Route::post('auth/login',[AuthController::class, 'login']);
//Route::get('/auth/logout',[AuthController::class, 'logout']);
Route::post('/update-profile',[AuthController::class,'updateProfile']);
Route::get('auth/get-user/{id}',[AuthController::class,'getUser']);
Route::middleware('auth:sanctum')->get('/auth/edit-profile/{id}',[AuthController::class,'editProfile']);
Route::post('/profile/change-password/{id}',[AuthController::class,'changePassword']);
Route::get('delete-customer/{id}',[AuthController::class,'deleteCustomerAccount']);



//Reset Password Api
Route::post('password/email',  [ResetPasswordController::class,'sendEmail']);
Route::post('password/code/check',  [ResetPasswordController::class,'checkVerificationCode']);
Route::post('password/reset',  [ResetPasswordController::class,'resetPassword']);

Route::prefix('v1')->group(function () {


    Route::get('quran-first-recitation-list',[RecitationController::class,'getQuranList']);
    Route::get('quran-first-recitation/{id}',[RecitationController::class,'getQuranById']);

    Route::get('quran-slow-recitation-list',[RecitationController::class,'getSlowQuranList']);
    Route::get('quran-slow-recitation/{id}',[RecitationController::class,'getSlowQuranById']);


    Route::get('tarawih-prayer-recitation-list',[RecitationController::class,'getTarawihList']);
    Route::get('tarawih-prayer-recitation/{id}',[RecitationController::class,'getTarawihById']);

    Route::get('regular-prayer-recitation-list',[RecitationController::class,'getRegularList']);
    Route::get('regular-prayer-recitation/{id}',[RecitationController::class,'getRegularById']);

    Route::get('khudbah-lecture-list',[RecitationController::class,'getKhudbahList']);
    Route::get('khudbah-lecture/{id}',[RecitationController::class,'getKhudbahById']);

    Route::get('khatira-lecture-list',[RecitationController::class,'getKhatiraList']);
    Route::get('khatira-lecture/{id}',[RecitationController::class,'getKhatiraById']);

    Route::get('dua-list',[RecitationController::class,'getDuaList']);
    Route::get('dua/{id}',[RecitationController::class,'getDuaById']);

    Route::get('hamd-nath-list',[RecitationController::class,'getHamdList']);
    Route::get('hamd-nath/{id}',[RecitationController::class,'getHamdById']);

    Route::get('get-tahajjud-prayer-list',[TahajjudPrayerListController::class,'getTahajjudPrayerList']);


    //Quran Profile
    Route::get('quran-profile/{customer}', [QuranProfileController::class, 'getQuranProfileListByCustomerId']);
    Route::post('quran-profile-create', [QuranProfileController::class, 'storeQuranProfileInformation']);
    Route::post('quran-profile-update', [QuranProfileController::class, 'updateQuranProfileInformation']);
    Route::get('quran-profile-delete/{id}', [QuranProfileController::class, 'deleteQuranProfile']);

    //Quran Tasks
    Route::post('store-tasks', [QuranStatusController::class, 'storeTasks']);
    Route::post('update-tasks/{id}', [QuranStatusController::class, 'updateTasks']);

    //Executable APIs
    Route::get('all-executable-tasks/{customer_id}/{profile_id}', [QuranStatusController::class, 'getAllExecutableTasks']);
    Route::get('executable-tasks-by-date/{customer_id}/{profile_id}/', [QuranStatusController::class, 'getExecutableTasksByDate']);
    Route::get('executable-tasks-by-time/{customer_id}/{profile_id}/', [QuranStatusController::class, 'getExecutableTasksByTime']);

    //Tracking APIs
    Route::get('all-tasks/{customer_id}/{profile_id}', [QuranStatusController::class, 'getAllTasks']);
    Route::get('tasks-by-date/{customer_id}/{profile_id}/', [QuranStatusController::class, 'getTasksByDate']);
    Route::get('tasks-by-time/{customer_id}/{profile_id}/', [QuranStatusController::class, 'getTasksByTime']);

    //My Quran Plan
    Route::post('my-quran-store-plans', [QuranPlanController::class, 'storePlans']);
    Route::get('my-quran-all-plans', [QuranPlanController::class, 'getAllPlans']);
    Route::get('delete-my-quran-plan/{id}', [QuranPlanController::class, 'deleteMyQuranPlan']);


    Route::get('quran-recitation-list',[QuranRecitationController::class,'getQuranRecitationList']);
    Route::get('tafsirul-quran-list',[TafsirulQuranListController::class,'getTafsirulQuranList']);
    Route::get('khutbah-list',[RecitationController::class,'getKhutbahList']);

    Route::get('akidah-list',[AkidahControllerController::class,'getAkidahList']);
    Route::get('darsul-hadith-list',[DarsulHadithController::class,'getDarsulHadithList']);


    Route::get('get-arabic-grammar-Category-list',[ArabicGrammarController::class,'getArabicGrammarList']);
    Route::get('get-arabic-grammar-list',[ArabicGrammarCategoryListController::class,'getArabicGrammerCategoryList']);

    Route::get('get-yasir-lecture-Category-list',[YasirLectureController::class,'getYasirLectureList']);
    Route::get('get-yasir-lecture-list',[YasirLectureCategoryController::class,'getYasirLectureCategoryList']);

    Route::get('get-jannah-jahannam-Category-list',[JannahJahannamController::class,'getJannahJahannamList']);
    Route::get('get-jannah-jahannam-list',[JannahJahannamCategoryController::class,'getJannahJahannamCategoryList']);

    Route::get('dua-list',[DuaListController::class,'getDuaList']);
    Route::get('message-of-quran',[MessageOfQuranController::class,'getMessageofQuran']);
    Route::get('ramandan-series',[RamandanSeriesController::class,'getRamadanSeries']);
    Route::get('stories-controller',[StoriesController::class,'getStories']);
});
