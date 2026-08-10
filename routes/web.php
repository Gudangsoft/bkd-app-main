<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdController;
use App\Http\Controllers\AssessorController;
use App\Http\Controllers\AssessorFee;
use App\Http\Controllers\AssessorFeeController;
use App\Http\Controllers\AssignmentLetterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentAssessorController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RekeningController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

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






// Route::middleware(['site_protection'])->group(function () {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/manual_book', [SettingController::class, 'manualBook']);

    // assessor
    Route::controller(AssessorController::class)->prefix('assessor')->group(function(){
        Route::get('data-dosen-assessor', 'dataDosenAssessor')->name('data-dosen-assessor');
        Route::get('asset-keuangan', 'assetKeuangan')->name('asset-keuangan');
    });

    Route::prefix('data')->group(function() {
        Route::get('master-data', [DataController::class, 'dataUser'])->name('data-user');
    });

    Route::resource('payments', PaymentController::class);
    Route::get('payments-trashed', [PaymentController::class,'restore']);

    Route::resource('assignment-letters', AssignmentLetterController::class);
    Route::get('/assessor/assignment-letters', [AssignmentLetterController::class, 'assignmentLetterAssessor'])->name('list-assignment-letter');

    Route::get('/payment-assessor-one/find/{aid}/{did}', [PaymentController::class, 'findAssessorOne']);
    Route::get('/payment-assessor-two/find/{aid}/{did}', [PaymentController::class, 'findAssessorTwo']);
    Route::get('/payment-invoice/{id}', [PaymentController::class, 'invoice'])->name('invoice');
    Route::get('/payment-download-invoice/{id}', [PaymentController::class, 'downloadInvoice'])->name('download-invoice');
    Route::get('/data-payment', [PaymentController::class, 'index'])->name('mypayment');

    Route::get('/profile/{id}', [UserController::class, 'profile'])->name('users.profile');
    Route::post('/profile-update', [UserController::class, 'profileUpdate'])->name('profile-update');
    Route::post('/profile-update-password', [UserController::class, 'profileUpdatePassword'])->name('profile-update-password');

    Route::resource('rekening-setting', RekeningController::class)->only(['index', 'store']);

    // finance
    Route::resource('assessor-fee', AssessorFeeController::class);

    Route::group(['middleware' => ['role:admin']], function () {
        // Route::resource('payments', PaymentController::class);
        Route::resource('payment-assessor', PaymentAssessorController::class);
        Route::resource('rekening', RekeningController::class);
        Route::resource('finances', FinanceController::class);
        Route::resource('users', UserController::class);
        Route::resource('settings', SettingController::class);
        Route::resource('ads', AdController::class);
    });

    // Deliberately outside the role:admin group: while impersonating, the
    // active session's role is the impersonated user's, not admin's, so
    // this route must stay reachable. UserController::stopLoginAs() checks
    // session('impersonator_id') itself instead of relying on middleware.
    Route::post('/login-as/stop', [UserController::class, 'stopLoginAs'])->name('login-as.stop');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::resource('/dashboard', DashboardController::class)->only(['index']);
});

/* Coming soon page */
Route::get('coming-soon', function() {
    return view('errors.coming-soon');
})->name('coming-soon');
