<?php

use App\Http\Controllers\AsnController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'captcha_url' => captcha_src('flat')
    ]);
})->name('home');

Route::get('/refresh-captcha', function() {
    return response()->json(['captcha_url' => captcha_src('flat')]);
});

// Endpoint Form & Ketersediaan Sesi
Route::post('/api/asn/check', [AsnController::class, 'check']);
Route::get('/api/consultations/booked', [ConsultationController::class, 'getBookedSessions']);
Route::post('/api/consultation/submit', [ConsultationController::class, 'store']);
Route::get('/api/consultations/calendar', [ConsultationController::class, 'getCalendarSessions']);

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES (ADMIN AREA)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [ConsultationController::class, 'dashboardAdmin'])->name('dashboard');

    // Hapus Route::get('/klien', function()...) yang lama, ganti dengan ini:
    Route::get('/klien', [App\Http\Controllers\ConsultationController::class, 'indexAdmin'])->name('klien');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
