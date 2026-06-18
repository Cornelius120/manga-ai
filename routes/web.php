<?php

// Kode ini diletakkan di routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\AuthController;

// Halaman Utama
Route::get('/', [MangaController::class, 'index'])->name('home');

// Jalur untuk yang BELUM login (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    // --- TAMBAHKAN DUA ROUTE INI ---
// Halaman Detail Komik
Route::get('/manga/{id}', [MangaController::class, 'show'])->name('manga.show');
// Halaman Baca Chapter
Route::get('/chapter/{id}', [MangaController::class, 'read'])->name('chapter.read');
});

// Jalur untuk yang SUDAH login (Auth)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});