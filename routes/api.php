<?php

use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
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

/**
 * Auth
 */
Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::get('/account-verify/{token}', 'verifyAccount');
    Route::post('login', 'login');
    Route::post('forgot-password', 'forgotPassword');
    Route::post('reset-password', 'resetPassword');
});

/**
 * Role 
 */
Route::controller(RoleController::class)->middleware(['auth:sanctum','user-permission'])->prefix('role')->group(function()
{
    Route::post('list', 'list');
    Route::post('create', 'create');
    Route::post('update/{id}', 'update');
    Route::get('get/{id}', 'get');
    Route::delete('delete/{id}', 'destroy');
});

/**
 * User
 */
Route::middleware(['auth:sanctum','throttle:1|30'])->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::post('list', 'list');
        Route::get('get/{id}', 'get');
        Route::get('logout', 'logout');
        Route::post('change-password', 'changePassword');
    });
});

/**
 * Module
 */
Route::controller(ModuleController::class)->prefix('module')->group(function () {
    Route::post('list', 'list');
    Route::post('create', 'create');
    Route::post('update/{id}', 'update');
    Route::get('get/{id}', 'get');
    Route::delete('delete/{id}', 'destroy');
});

/**
 * Permission
 */
Route::controller(PermissionController::class)->prefix('permission')->group(function () {
    Route::post('list', 'list');
    Route::post('create', 'create');
    Route::post('update/{id}', 'update');
    Route::get('get/{id}', 'get');
    Route::delete('delete/{id}', 'destroy');
});