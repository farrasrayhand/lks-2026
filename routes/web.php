<?php

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\CitizenController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\HealthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/storage/uploads/{path}', function (string $path) {
    $file = Storage::disk('uploads')->path($path);
    if (!file_exists($file)) {
        abort(404);
    }
    return response()->file($file);
})->where('path', '.*');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/health', [HealthController::class, 'page']);

Route::get('/chatbot', [ChatbotController::class, 'page']);

Route::get('/api-info', function () {
    return view('api-info');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/citizen/dashboard', [CitizenController::class, 'dashboard']);
    Route::get('/citizen/services', [CitizenController::class, 'services']);
    Route::post('/citizen/services', [CitizenController::class, 'storeService']);
    Route::get('/citizen/services/{id}', [CitizenController::class, 'showService']);
    Route::get('/citizen/reports', [CitizenController::class, 'reports']);
    Route::post('/citizen/reports', [CitizenController::class, 'storeReport']);
    Route::get('/citizen/notifications', [CitizenController::class, 'notifications']);

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/admin/services', [AdminController::class, 'services']);
    Route::post('/admin/services/{id}/status', [AdminController::class, 'updateServiceStatus']);
    Route::get('/admin/reports', [AdminController::class, 'reports']);
    Route::post('/admin/reports/{id}/status', [AdminController::class, 'updateReportStatus']);
});
