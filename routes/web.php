<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DailyCalculatorController;
use App\Http\Controllers\MonthlyCalculatorController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->is_admin 
            ? redirect()->route('monitoring.index') 
            : redirect()->route('harian');
    }
    return redirect()->route('login');
});

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Technician-only Routes
    Route::middleware('technician')->group(function () {
        // Daily Tab
        Route::get('/harian', [DailyCalculatorController::class, 'index'])->name('harian');
        Route::post('/harian', [DailyCalculatorController::class, 'store'])->name('harian.store');

        // Monthly Tab
        Route::get('/bulanan', [MonthlyCalculatorController::class, 'index'])->name('bulanan');
        Route::get('/bulanan/export', [MonthlyCalculatorController::class, 'exportCsv'])->name('bulanan.export');
    });

    // Admin-only Routes
    Route::middleware('admin')->group(function () {
        // Salary Monitoring
        Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
        Route::get('/monitoring/{user}', [MonitoringController::class, 'show'])->name('monitoring.show');

        // Tarif Configuration
        Route::get('/tarif', [TarifController::class, 'index'])->name('tarif.index');
        Route::post('/tarif', [TarifController::class, 'update'])->name('tarif.update');

        // User Management
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users/{user}/toggle-role', [UserController::class, 'toggleRole'])->name('users.toggle-role');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});

Route::get('/php-info', function () {
    return response()->json([
        'php_version' => PHP_VERSION,
        'extensions' => get_loaded_extensions(),
    ]);
});
