<?php

use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\BalanceSheets\DailyBalanceController;
use App\Http\Controllers\BalanceSheets\MonthlyBalanceController;
use App\Http\Controllers\BalanceSheets\ProductController;
use App\Http\Controllers\BalanceSheets\StatisticsController;
use App\Http\Controllers\BalanceSheets\TargetController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Picture\PictureRenderController;
use App\Http\Controllers\Users\UserController;
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

// Route::get('/', 'Auth\LoginController@loginView');
Route::get('/', function () {
    return view('auth.login');
});
// socialite routes
Route::get('auth/google', [SocialController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [SocialController::class, 'handleGoogleCallback']);

Route::get('auth/facebook', [SocialController::class, 'redirectToFacebook']);
Route::get('auth/facebook/callback', [SocialController::class, 'handleFacebookCallback']);

Route::get('confirm-delete-account/{token}', [ProfileController::class, 'confirmDeleteAccount'])->name('confirm-delete-account');
Route::get('billing/users/accept-invitation/{token}', [CompanyUserController::class, 'acceptInvitation'])->name('billing/users/accept-invitation');
// Route::post('login', 'Auth\LoginController@authenticate')->name('authenticate');
// Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('user/picture/{info}', [PictureRenderController::class, 'renderUserPicture'])->name('user/picture');
Route::get('mediafiles/picture/{info}', [PictureRenderController::class, 'renderMediaPicture'])->name('mediafiles/picture');

Route::group(['middleware' => ['auth', 'verified', 'checkstatus']], function () {
    // the get routes are passed through user.format and menu middleware
    Route::group(['middleware' => ['user.format', 'menu.side']], function () {
        // dashboard view
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // account views
        Route::get('account/profile', [ProfileController::class, 'profile'])->name('account/profile');
        Route::get('account/settings', [ProfileController::class, 'settings'])->name('account/settings');

        // user views
        Route::get('users', [UserController::class, 'index'])->name('users');
        Route::get('users/edit/{id}', [UserController::class, 'edit'])->name('users/edit');
        Route::get('users/create', [UserController::class, 'create'])->name('users/create');

        /**
         * Balance sheet module - start
         */

        // targets views
        Route::get('balancesheet/targets', [TargetController::class, 'index'])->name('balancesheet/targets');
        Route::get('balancesheet/targets/edit/{id}', [TargetController::class, 'edit'])->name('balancesheet/targets/edit');
        Route::get('balancesheet/targets/create', [TargetController::class, 'create'])->name('balancesheet/targets/create');

        // monthly balance sheet views
        Route::get('balancesheet/monthly-balance', [MonthlyBalanceController::class, 'index'])->name('balancesheet/monthly-balance');
        Route::get('balancesheet/monthly-balance/edit/{id}', [MonthlyBalanceController::class, 'edit'])->name('balancesheet/monthly-balance/edit');
        Route::get('balancesheet/monthly-balance/create', [MonthlyBalanceController::class, 'create'])->name('balancesheet/monthly-balance/create');

        // daily balance sheet views
        Route::get('balancesheet/daily-balance', [DailyBalanceController::class, 'index'])->name('balancesheet/daily-balance');
        Route::get('balancesheet/daily-balance/edit/{id}', [DailyBalanceController::class, 'edit'])->name('balancesheet/daily-balance/edit');
        Route::get('balancesheet/daily-balance/create', [DailyBalanceController::class, 'create'])->name('balancesheet/daily-balance/create');

        // balance statistics view
        Route::get('balancesheet/statistics', [StatisticsController::class, 'index'])->name('balancesheet/statistics');

        /**
         * Balance sheet module - end
         */

    });

    // account actions
    Route::post('account/settings/update-profile', [ProfileController::class, 'updateProfile'])->name('account/settings/update-profile');
    Route::post('account/settings/update-password', [ProfileController::class, 'updatePassword'])->name('account/settings/update-password');
    Route::post('account/settings/update-connections', [ProfileController::class, 'updateConnectedAccounts'])->name('account/settings/update-connections');
    Route::post('account/setttings/delete-account', [ProfileController::class, 'deleteAccount'])->name('account/settings/delete-account');

    // user actions
    Route::get('users/list', [UserController::class, 'list'])->name('users/list');
    Route::post('users/store', [UserController::class, 'store'])->name('users/store');
    Route::post('users/update/{id}', [UserController::class, 'update'])->name('users/update');
    Route::delete('users/delete/{id}', [UserController::class, 'delete'])->name('users/delete');
    Route::delete('users/remove/{id}', [UserController::class, 'remove'])->name('users/remove');
    Route::post('users/activate/{id}', [UserController::class, 'activate'])->name('users/activate');
    Route::post('users/deactivate/{id}', [UserController::class, 'deactivate'])->name('users/deactivate');
    Route::post('users/update-password/{id}', [UserController::class, 'updatePassword'])->name('users/update-password');
    Route::post('users/update-email/{id}', [UserController::class, 'updateEmail'])->name('users/update-email');
    Route::post('users/export', [UserController::class, 'export'])->name('users/export');

    /**
     * Balance sheet module - start
     */

    // target actions
    Route::get('balancesheet/targets/list', [TargetController::class, 'list'])->name('balancesheet/targets/list');
    Route::post('balancesheet/targets/store', [TargetController::class, 'store'])->name('balancesheet/targets/store');
    Route::post('balancesheet/targets/update/{id}', [TargetController::class, 'update'])->name('balancesheet/targets/update');
    Route::delete('balancesheet/targets/delete/{id}', [TargetController::class, 'delete'])->name('balancesheet/targets/delete');
    Route::post('balancesheet/targets/export', [TargetController::class, 'export'])->name('balancesheet/targets/export');

    // monthly balance sheet actions
    Route::get('balancesheet/monthly-balance/list', [MonthlyBalanceController::class, 'list'])->name('balancesheet/monthly-balance/list');
    Route::post('balancesheet/monthly-balance/store', [MonthlyBalanceController::class, 'store'])->name('balancesheet/monthly-balance/store');
    Route::post('balancesheet/monthly-balance/update/{id}', [MonthlyBalanceController::class, 'update'])->name('balancesheet/monthly-balance/update');
    Route::delete('balancesheet/monthly-balance/delete/{id}', [MonthlyBalanceController::class, 'delete'])->name('balancesheet/monthly-balance/delete');
    Route::post('balancesheet/monthly-balance/export', [MonthlyBalanceController::class, 'export'])->name('balancesheet/monthly-balance/export');

    // daily balance sheet actions
    Route::get('balancesheet/daily-balance/list', [DailyBalanceController::class, 'list'])->name('balancesheet/daily-balance/list');
    Route::post('balancesheet/daily-balance/store', [DailyBalanceController::class, 'store'])->name('balancesheet/daily-balance/store');
    Route::post('balancesheet/daily-balance/update/{id}', [DailyBalanceController::class, 'update'])->name('balancesheet/daily-balance/update');
    Route::delete('balancesheet/daily-balance/delete/{id}', [DailyBalanceController::class, 'delete'])->name('balancesheet/daily-balance/delete');
    Route::post('balancesheet/daily-balance/export', [DailyBalanceController::class, 'export'])->name('balancesheet/daily-balance/export');

    // balance statistics data
    Route::get('balancesheet/statistics/chart', [StatisticsController::class, 'list'])->name('balancesheet/statistics/chart');

    // product autocomplete
    Route::get('products/suggestions', [ProductController::class, 'search']);

    /**
     * Balance sheet module - end
     */

});
