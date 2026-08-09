<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DailyCalculatorController;
use App\Http\Controllers\MonthlyCalculatorController;
use App\Http\Controllers\TarifController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('harian');
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

    // Daily Tab
    Route::get('/harian', [DailyCalculatorController::class, 'index'])->name('harian');
    Route::post('/harian', [DailyCalculatorController::class, 'store'])->name('harian.store');

    // Monthly Tab
    Route::get('/bulanan', [MonthlyCalculatorController::class, 'index'])->name('bulanan');
    Route::get('/bulanan/export', [MonthlyCalculatorController::class, 'exportCsv'])->name('bulanan.export');

    // Tarif Configuration
    Route::get('/tarif', [TarifController::class, 'index'])->name('tarif.index');
    Route::post('/tarif', [TarifController::class, 'update'])->name('tarif.update');
});
