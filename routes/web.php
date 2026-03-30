<?php

use App\Http\Controllers\AsnController;
use App\Http\Controllers\ConsultationController;

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
// Frontpage Konsultasi
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/', function () {
    return Inertia::render('Welcome', [
        // Kirim URL gambar captcha awal saat halaman pertama dimuat
        'captcha_url' => captcha_src('flat')
    ]);
})->name('home');

// Endpoint untuk refresh gambar captcha (dipanggil via Axios di Vue)
Route::get('/refresh-captcha', function() {
    return response()->json(['captcha_url' => captcha_src('flat')]);
});

// Endpoint Form
Route::post('/api/asn/check', [AsnController::class, 'check']);
Route::post('/api/consultation/submit', [ConsultationController::class, 'store']);

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES (ADMIN AREA)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard'); // Pastikan ini mengarah ke file Vue dashboard yang benar
    })->name('dashboard');

    // Halaman Tabel Klien
    Route::get('/klien', function () {
        return Inertia::render('Klien/Index'); // Pastikan ini mengarah ke file Vue klien yang benar
    })->name('klien');

    // Profile Management (Bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (BREEZE)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
