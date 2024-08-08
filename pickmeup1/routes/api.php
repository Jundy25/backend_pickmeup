<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RiderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\HistoryController;
use App\Models\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;


//USER MANAGEMENT
Route::prefix('/user')->group(function() {
    Route::post('/login', [AuthController::class, 'loginAccount']);
    Route::post('/loginMob', [AuthController::class, 'loginAccountMobile']);
    Route::post('/signup', [AuthController::class, 'createAccount']);
    Route::patch('/me', [AuthController::class, 'accountUpdate'])->middleware(['auth:sanctum']);
    Route::post('/logout', [AuthController::class, 'logoutAccount'])->middleware(['auth:sanctum']);
    Route::get('/rider', [AuthController::class, 'showRider']);
    Route::get('/customer', [AuthController::class, 'showCustomer']);
    Route::get('/dashboard/counts', [DashboardController::class, 'getCounts']);
    Route::get('/riders', [RiderController::class, 'getRiders']);
    Route::put('rider/{user_id}/status', [CustomerController::class, 'updateStatus']);

    Route::get('/customers', [CustomerController::class, 'getCustomers']);
    Route::put('customer/{user_id}/status', [CustomerController::class, 'updateStatus']);

    Route::get('/admin', [AdminController::class, 'getAdmin']);
    Route::get('/admin/{id}', [AdminController::class, 'show']);
    Route::put('admin/{user_id}/status', [AdminController::class, 'updateStatus']);
    Route::put('/update_admin/{id}', [AdminController::class, 'updateAdmin']);
    
    Route::get('/history', [HistoryController::class, 'index']);
    Route::get('/feedbacks', [FeedbackController::class, 'index']);
});

