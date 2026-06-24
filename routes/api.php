<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/health', [HealthController::class, 'check']);
Route::post('/chatbot', [ChatbotController::class, 'chat']);

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('profile', [AuthController::class, 'profile']);
    });
});

Route::prefix('services')->group(function () {
    Route::get('/', [ServiceController::class, 'index']);

    Route::middleware('auth:api')->group(function () {
        Route::post('request', [ServiceController::class, 'request']);
        Route::get('request/{id}', [ServiceController::class, 'showRequest']);
        Route::get('requests', [ServiceController::class, 'listRequests']);
        Route::put('request/{id}/status', [ServiceController::class, 'updateStatus'])
            ->middleware('role:admin');
    });
});

Route::prefix('reports')->middleware('auth:api')->group(function () {
    Route::post('/', [ReportController::class, 'store']);
    Route::get('/', [ReportController::class, 'index']);
    Route::get('{id}', [ReportController::class, 'show']);
    Route::put('{id}', [ReportController::class, 'update']);
});

Route::prefix('notifications')->middleware('auth:api')->group(function () {
    Route::get('/', [NotificationController::class, 'index']);
});

Route::prefix('dashboard')->middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('stats', [DashboardController::class, 'stats']);
    Route::get('reports/summary', [DashboardController::class, 'reportsSummary']);
});
