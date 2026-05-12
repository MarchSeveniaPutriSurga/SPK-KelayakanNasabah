<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SmartController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CriterionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\ScoringParameterController;

// Route::get('/', fn() => redirect()->route('smart.index'));

Route::get('/', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.index');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Criteria
    Route::resource('criteria', CriterionController::class);

    // Parameters scoring
    Route::resource('parameters', ScoringParameterController::class);

    // Periods
    Route::resource('periods', PeriodController::class)->except(['show']);
    Route::get('periods/{id}/activate', [PeriodController::class, 'setActive'])->name('periods.activate');

    // Customers
    Route::resource('customers', CustomerController::class);

    // Penilaian (input nilai real)
    Route::get('penilaian/create', [PenilaianController::class, 'create'])->name('penilaian.create');
    Route::post('penilaian/store', [PenilaianController::class, 'store'])->name('penilaian.store');

    // SMART ranking
    Route::get('smart', [SmartController::class, 'index'])->name('smart.index');

    //Riwayat Penilaian
    Route::get('/riwayat-penilaian', [PenilaianController::class, 'riwayat'])
        ->name('penilaian.riwayat');
});
