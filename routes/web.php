<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CriterionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScoringParameterController;
use App\Http\Controllers\SmartController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\Request;

// Route::get('/', fn() => redirect()->route('smart.index'));

Route::get('/clear', function () {
    Artisan::call('config:clear');
    return 'Cleared!';
});

Route::get('/', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.index');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', [
        'token' => $token,
        'email' => request('email'),
    ]);
})->name('password.reset');

Route::post('/reset-password', function (Request $request) {

    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ], [
        'password.required' => 'Password baru wajib diisi.',
        'password.min' => 'Password baru minimal 8 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak sesuai.',
    ]);

    $status = Password::reset(
        $request->only(
            'email',
            'password',
            'password_confirmation',
            'token'
        ),
        function ($user, $password) {
            $user->password = $password;
            $user->save();
        }
    );

    if ($status === Password::PASSWORD_RESET) {
        return redirect()
            ->route('login')
            ->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
    }

    return back()->withErrors([
        'email' => __($status),
    ]);
})->name('password.update');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route::get('/profil', function () {
    //     return view('profile.index');
    // })->name('profile.index');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');

    Route::get('/profil/password', [ProfileController::class, 'editPassword'])->name('profile.password.edit');

    Route::put('/profil/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::get('/profil/email', [ProfileController::class, 'editEmail'])->name('profile.email.edit');

    Route::put('/profil/email', [ProfileController::class, 'updateEmail'])->name('profile.email.update');

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
    Route::get('/riwayat-penilaian', [PenilaianController::class, 'riwayat'])->name('penilaian.riwayat');

    // export
    Route::get('/smart/export/excel', [SmartController::class, 'exportExcel'])->name('smart.export.excel');
    Route::get('/smart/export/pdf',   [SmartController::class, 'exportPdf'])->name('smart.export.pdf');
});
