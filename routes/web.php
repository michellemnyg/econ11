<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| PUBLIC / DEFAULT
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

/*
|--------------------------------------------------------------------------
| 🔓 DEV ROUTES (TANPA AUTH) — FASE 0 & 1
|--------------------------------------------------------------------------
| ⚠️ HANYA UNTUK DEVELOPMENT
| ⚠️ HAPUS / NONAKTIFKAN SAAT PRODUKSI
*/

Route::prefix('dev')->group(function () {
    // Preview UI Login (tanpa logic auth)
    Route::get('/login', function () {
        return Inertia::render('Auth/Login');
    });

    // Preview Dashboard Admin
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    });

    // Preview Halaman Klien
    Route::get('/klien', function () {
        return Inertia::render('Klien/Index');
    });
});

/*
|--------------------------------------------------------------------------
| 🔐 AUTHENTICATED ROUTES (REAL FLOW)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
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
