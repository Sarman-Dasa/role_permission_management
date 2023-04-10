<?php

use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\web\HomeController;
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

/**
 * Auth
 */
Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::get('/account-verify/{token}', 'verifyAccount');
    Route::post('login', 'login');
    Route::post('forgot-password', 'forgotPassword');
    Route::post('reset-password', 'resetPassword');
    Route::post("login-with-mobile",'sendOtp')->name('sendOtp');
    Route::post("verify-otp",'verifyOtp');
});

/**
 * Role 
 */
Route::controller(RoleController::class)->middleware(['auth:sanctum'])->prefix('role')->group(function()
{
    Route::post('list', 'list')->middleware('user-permission:Role,view_access');
    Route::post('create', 'create')->middleware('user-permission:Role,add_access');
    Route::post('update/{id}', 'update')->middleware('user-permission:Role,update_access');
    Route::get('get/{id}', 'get')->middleware('user-permission:Role,view_access');
    Route::delete('delete/{id}', 'destroy')->middleware('user-permission:Role,delete_access');
});

/**
 * User
 */
Route::middleware(['auth:sanctum','throttle:1|30'])->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::post('list', 'list');
        Route::post('update', 'update');
        Route::get('get/{id}', 'get');
        Route::delete('delete','destroy');
        Route::get('logout', 'logout');
        Route::post('change-password', 'changePassword');
    });
});

/**
 * Module
 */
Route::controller(ModuleController::class)->middleware(['auth:sanctum'])->prefix('module')->group(function () {
    Route::post('list', 'list')->middleware('user-permission:Module,view_access');
    Route::post('create', 'create')->middleware('user-permission:Module,add_access');
    Route::post('update/{id}', 'update')->middleware('user-permission:Module,update_access');
    Route::get('get/{id}', 'get')->middleware('user-permission:Module,view_access');
    Route::delete('delete/{id}', 'destroy')->middleware('user-permission:Module,delete_access');
});
/**
 * Permission
 */
Route::controller(PermissionController::class)->middleware(['auth:sanctum'])->prefix('permission')->group(function () {
    Route::post('list', 'list')->middleware('user-permission:Permission,view_access');
    Route::post('create', 'create')->middleware('user-permission:Permission,add_access');
    Route::post('update/{id}', 'update')->middleware('user-permission:Permission,update_access');
    Route::get('get/{id}', 'get')->middleware('user-permission:Permission,view_access');
    Route::delete('delete/{id}', 'destroy')->middleware('user-permission:Permission,delete_access');
});

/**
 * Employee Data Manage
 * Hr has full Access 
 * Employee only list and get the data 
 */
Route::controller(EmployeeController::class)->middleware(['auth:sanctum'])->prefix('employee')->group(function(){
    Route::post('list', 'list')->middleware('user-permission:Employee,view_access');
    Route::post('create', 'create')->middleware('user-permission:Employee,add_access');
    Route::post('update/{id}', 'update')->middleware('user-permission:Employee,update_access');
    Route::get('get/{id}', 'get')->middleware('user-permission:Employee,view_access');
    Route::delete('delete/{id}', 'destroy')->middleware('user-permission:Employee,delete_access');
});


/**
 * Task Data Manage
 * Hr has full Access 
 * Employee only list and get the data 
 */
Route::controller(TaskController::class)->middleware(['auth:sanctum'])->prefix('task')->group(function(){
    Route::post('list', 'list')->middleware('user-permission:Task,view_access');
    Route::post('create', 'create')->middleware('user-permission:Task,add_access');
    Route::post('update/{id}', 'update')->middleware('user-permission:Task,update_access');
    Route::get('get/{id}', 'get')->middleware('user-permission:Task,view_access');
    Route::delete('delete/{id}', 'destroy')->middleware('user-permission:Task,delete_access');
});

/**
 * Route For check custome facade
 */

 Route::get('it-date-class',function()
 {
    // $getYMD = myDate::dateFormatYMD('06/04/2023');
    // return $getYMD;
    $dateYMD =  myDate::dateFormatYMD('06/04/2023');
    $dateDMY = mydate::dateFormatMDY('2023-04-06');
    return ["YMD " => $dateYMD,"DMY " => $dateDMY];
 });
/**
 * Save Device token and send push notification 
 */
 Route::post('/save-token', [HomeController::class, 'saveToken'])->name('save-token');
 Route::post('/send-notification', [HomeController::class, 'sendNotification'])->name('send.notification');

/**
 * Gates middleware 
 */
Route::controller(ProductController::class)->middleware(['auth:sanctum'])->prefix('product')->group(function()
{
    Route::post('list', 'list')->middleware('can:isAdmin');
    Route::post('create', 'create')->middleware('can:isHr');
    Route::post('update', 'update')->middleware('can:isAdmin');
    Route::get('get', 'get')->middleware('can:isEmployee');
    Route::delete('delete', 'destroy')->middleware('can:isAdmin');
});

Route::controller(OrderController::class)->middleware(['auth:sanctum'])->prefix('order')->group(function()
{
    Route::post('list', 'list');
    Route::post('create', 'create');
    Route::post('update', 'update');
    Route::get('get', 'get');
    Route::delete('delete', 'destroy');
});

