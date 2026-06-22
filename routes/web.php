<?php

// Kode ini diletakkan di routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AdminController;
// Pastikan controller baru ini di-import di bagian atas
use App\Http\Controllers\AdminChapterController;
use App\Http\Controllers\SearchController;

// 1. BISA DIAKSES SEMUA ORANG (Guest & Auth)
Route::get('/', [MangaController::class, 'index'])->name('home');
// Halaman Detail Komik (Sekarang mencari berdasarkan slug)
Route::get('/komik/{slug}', [MangaController::class, 'show'])->name('manga.show');
// Halaman Baca Chapter (Mencari berdasarkan slug komik dan nomor chapter)
Route::get('/komik/{slug}/chapter/{chapter_number}', [MangaController::class, 'read'])->name('chapter.read');
// --- RUTE PENCARIAN AI ---
Route::get('/search', [SearchController::class, 'index'])->name('search');

// 2. HANYA UNTUK YANG BELUM LOGIN (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// 3. HANYA UNTUK YANG SUDAH LOGIN (Auth)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update-info', [ProfileController::class, 'updateInfo'])->name('profile.update.info');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update.password');
    Route::post('/profile/upload-avatar', [ProfileController::class, 'uploadAvatar'])->name('profile.upload.avatar');
    Route::post('/comment/{chapter_id}', [CommentController::class, 'store'])->name('comment.store');
    // --- RUTE LIBRARY & BOOKMARK ---
    Route::get('/library', [\App\Http\Controllers\LibraryController::class, 'index'])->name('library');
    Route::post('/library/bookmark/{manga_id}', [\App\Http\Controllers\LibraryController::class, 'toggleBookmark'])->name('bookmark.toggle');
    
    // --- RUTE BALASAN KOMENTAR ---
    Route::get('/komentar/{id}', [\App\Http\Controllers\CommentController::class, 'showThread'])->name('comment.thread');
    Route::post('/komentar/{id}/reply', [\App\Http\Controllers\CommentController::class, 'reply'])->name('comment.reply');

    // --- RUTE KOMENTAR KOMIK & PROFIL PUBLIK ---
Route::post('/komik/{id}/comment', [\App\Http\Controllers\CommentController::class, 'storeMangaComment'])->name('manga.comment.store');
Route::get('/user/{id}', [\App\Http\Controllers\ProfileController::class, 'showPublic'])->name('profile.public');

    // --- RUTE PANEL ADMIN ---
    Route::get('/admin/manga', [AdminController::class, 'index'])->name('admin.manga.index');
    Route::get('/admin/manga/create', [AdminController::class, 'create'])->name('admin.manga.create');
    Route::post('/admin/manga', [AdminController::class, 'store'])->name('admin.manga.store');
    Route::delete('/admin/manga/{id}', [AdminController::class, 'destroy'])->name('admin.manga.destroy');
    // --- RUTE TAMBAH CHAPTER ADMIN ---
    Route::get('/admin/manga/{manga_id}/chapter/create', [AdminChapterController::class, 'create'])->name('admin.chapter.create');
    Route::post('/admin/manga/{manga_id}/chapter', [AdminChapterController::class, 'store'])->name('admin.chapter.store');
    // Kode ini diletakkan di routes/web.php (Di dalam grup admin)
// Kode ini diletakkan di routes/web.php (Di dalam grup admin)
Route::get('/admin/chapter', [\App\Http\Controllers\AdminChapterController::class, 'index'])->name('admin.chapter.index');
// Rute untuk mengelola Chapter
Route::get('/admin/chapter/{id}/edit', [\App\Http\Controllers\AdminChapterController::class, 'edit'])->name('admin.chapter.edit');
Route::put('/admin/chapter/{id}', [\App\Http\Controllers\AdminChapterController::class, 'update'])->name('admin.chapter.update');
Route::delete('/admin/chapter/{id}', [\App\Http\Controllers\AdminChapterController::class, 'destroy'])->name('admin.chapter.destroy');
// Rute khusus untuk menghapus satu gambar/halaman tertentu
Route::delete('/admin/page/{id}', [\App\Http\Controllers\AdminChapterController::class, 'destroyPage'])->name('admin.page.destroy');
// Rute untuk menampilkan halaman form edit
Route::get('/admin/manga/{id}/edit', [\App\Http\Controllers\AdminController::class, 'edit'])->name('admin.manga.edit');
// Rute untuk memproses pembaruan data (menggunakan PUT)
Route::put('/admin/manga/{id}', [\App\Http\Controllers\AdminController::class, 'update'])->name('admin.manga.update');
// Rute untuk mengeksekusi penghapusan komik (menggunakan DELETE)
Route::delete('/admin/manga/{id}', [\App\Http\Controllers\AdminController::class, 'destroy'])->name('admin.manga.destroy');
// Kode ini diletakkan di routes/web.php (Di dalam grup admin)
Route::get('/admin/comment', [\App\Http\Controllers\AdminCommentController::class, 'index'])->name('admin.comment.index');
Route::delete('/admin/comment/{id}', [\App\Http\Controllers\AdminCommentController::class, 'destroy'])->name('admin.comment.destroy');
// Kode ini diletakkan di routes/web.php (Di dalam grup admin)
Route::get('/admin/ad', [\App\Http\Controllers\AdminAdController::class, 'index'])->name('admin.ad.index');
Route::get('/admin/ad/create', [\App\Http\Controllers\AdminAdController::class, 'create'])->name('admin.ad.create');
Route::post('/admin/ad', [\App\Http\Controllers\AdminAdController::class, 'store'])->name('admin.ad.store');
Route::get('/admin/ad/{id}/edit', [\App\Http\Controllers\AdminAdController::class, 'edit'])->name('admin.ad.edit');
Route::put('/admin/ad/{id}', [\App\Http\Controllers\AdminAdController::class, 'update'])->name('admin.ad.update');
Route::delete('/admin/ad/{id}', [\App\Http\Controllers\AdminAdController::class, 'destroy'])->name('admin.ad.destroy');
// Rute Pengaturan Web
Route::get('/admin/setting', [\App\Http\Controllers\AdminSettingController::class, 'index'])->name('admin.setting.index');
Route::put('/admin/setting/update', [\App\Http\Controllers\AdminSettingController::class, 'update'])->name('admin.setting.update');
});