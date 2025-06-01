<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\HomePageController;
use App\Http\Controllers\Api\SubCategoryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::group(['middleware' => ['api', 'limitReq']], function ($router) {
Route::group(['middleware' => ['api']], function ($router) {
    Route::get('/return-true', function () {
        return response()->json(false);
    });
    // package
    Route::get('packages', [PackageController::class, 'index']);
    Route::group(['prefix' => 'auth'], function ($router) {
        Route::post('register', [AuthController::class, 'register']); // for user or vendor
        Route::post('branches/store', [AuthController::class, 'addBranch'])->middleware('auth:api'); // for vendor only        Route::post('login', [AuthController::class, 'login']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('regenerate-code', [AuthController::class, 'regenerateCode']);
        Route::post('email-check', [AuthController::class, 'emailCheck']);


        Route::post('user/update-profile', [AuthController::class, 'updateProfile'])->middleware('auth:api');
        Route::post('user/verify-new-email', [AuthController::class, 'verifyNewEmail'])->middleware('auth:api');

        Route::post('send-reset-code', [AuthController::class, 'sendResetCode']);
        Route::post('verify-reset-code', [AuthController::class, 'verifyResetCode']);
        Route::post('reset', [AuthController::class, 'resetPassword']);
        Route::post('change-password', [AuthController::class, 'changePassword'])->middleware('auth:api');
    });

    Route::get('payments/{page?}', [HomeController::class, 'payments']);
    Route::get('branch/{id?}', [BranchController::class, 'branch']);
    Route::get('branches/details/{id?}', [BranchController::class, 'details']);
    Route::post('get/branches/{page?}', [BranchController::class, 'branches']);
    Route::get('categories/{page?}', [HomeController::class, 'categories']);
    Route::get('sub-categories/{category_id}', [SubCategoryController::class, 'getByCategory']);
    Route::get('sub-category/{id}/branches', [HomeController::class, 'subCategoryBranches']);
    Route::get('blogs/{page?}', [HomeController::class, 'blogs']);
    Route::get('blog/{id?}', [HomeController::class, 'blog']);
    Route::get('abouts/{type?}', [HomeController::class, 'abouts']);
    Route::get('countries', [CountryController::class, 'index']);
    Route::post('nearest-branches', [HomePageController::class, 'nearestBranches']);


    Route::post('contacts/store', [ContactController::class, 'store']);

    Route::group(['middleware' => 'userActivation'], function ($router) {

        Route::group(['prefix' => 'auth'], function ($router) {
            Route::get('/', [AuthController::class, 'me']);
            Route::get('user/branches', [AuthController::class, 'userBranches']);
            Route::get('refresh', [AuthController::class, 'refresh']);
            Route::post('/change-mobile-number', [AuthController::class, 'changeMobileNum']);
            Route::post('/update', [AuthController::class, 'userUpdate']);
            Route::post('user/upload-photo', [AuthController::class, 'uploadProfilePhoto'])->middleware('auth:api');
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('delete-account', [AuthController::class, 'deleteAccount']);
            Route::post('update-email', [AuthController::class, 'updateEmail']);
            Route::post('verify-email', [AuthController::class, 'verifyEmail']);
            Route::post('pay-package', [PaymentController::class, 'generatePaymentUrl'])->middleware('auth:api');
            Route::get('package-history/{limt}/{page?}', [PaymentController::class, 'history'])->middleware('auth:api');
        });

        Route::group(['prefix' => 'favorites'], function ($router) {
            Route::get('get', [AuthController::class, 'getFavorites']);
            Route::post('add/{id?}', [AuthController::class, 'addFavorites']);
        });

        Route::group(['prefix' => 'branches'], function ($router) {
            Route::post('store', [BranchController::class, 'store']);
            Route::post('update/{id?}', [BranchController::class, 'update']);
        });

        Route::post('test', [HomeController::class, 'test']);
    });
});
// Paymob Callback
Route::get('paymob/state', [PaymentController::class, 'state']);
Route::get('settings', [PaymentController::class, 'settings']);
Route::get('settings/all', [HomeController::class, 'settings']);
