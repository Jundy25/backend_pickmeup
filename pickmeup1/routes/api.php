<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\BudgetController;
use App\Models\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;


//USER MANAGEMENT
Route::prefix('/user')->group(function() {
    Route::post('/login', [AuthController::class, 'loginAccount']);
    Route::post('/signup', [AuthController::class, 'createAccount']);
    Route::get('/me', [AuthController::class, 'show'])->middleware(['auth:sanctum']);
    Route::patch('/me', [AuthController::class, 'accountUpdate'])->middleware(['auth:sanctum']);
    Route::post('/logout', [AuthController::class, 'logoutAccount'])->middleware(['auth:sanctum']);
    Route::get('/rider', [AuthController::class, 'showRider']);
    Route::get('/customer', [AuthController::class, 'showCustomer']);
});

