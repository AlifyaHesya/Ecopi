<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PengajuanDonasiController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

// ─────────────────────────────────────────
// PUBLIC ROUTES (tidak perlu login)
// ─────────────────────────────────────────

// Halaman utama / beranda
Route::get('/', [ItemController::class, 'index'])->name('home');
Route::get('/items', [ItemController::class, 'index'])->name('items.index');

// Katalog & pencarian barang
Route::get('/katalog', [ItemController::class, 'search'])->name('items.search');
Route::get('/barang/{item}', [ItemController::class, 'show'])->name('items.show');

// ─────────────────────────────────────────
// AUTH ROUTES (Register & Login)
// ─────────────────────────────────────────

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ─────────────────────────────────────────
// PROTECTED ROUTES (harus login)
// ─────────────────────────────────────────

Route::middleware('auth')->group(function () {

    // Donasi Barang
    Route::get('/donasi/buat', [ItemController::class, 'create'])->name('items.create');
    Route::post('/donasi', [ItemController::class, 'store'])->name('items.store');
    Route::get('/donasi/{item}/edit', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/donasi/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/donasi/{item}', [ItemController::class, 'destroy'])->name('items.destroy');

    // Pengajuan Donasi
    Route::get('/pengajuan', [PengajuanDonasiController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan/buat', [PengajuanDonasiController::class, 'create'])->name('pengajuan.create');
    Route::post('/pengajuan', [PengajuanDonasiController::class, 'store'])->name('pengajuan.store');
    Route::get('/pengajuan/status', [PengajuanDonasiController::class, 'status'])->name('pengajuan.status');
    Route::get('/pengajuan/masuk', [PengajuanDonasiController::class, 'masuk'])->name('pengajuan.masuk');
    Route::get('/pengajuan/{pengajuanDonasi}', [PengajuanDonasiController::class, 'show'])->name('pengajuan.show');
    Route::put('/pengajuan/{pengajuanDonasi}', [PengajuanDonasiController::class, 'update'])->name('pengajuan.update');
    Route::delete('/pengajuan/{pengajuanDonasi}', [PengajuanDonasiController::class, 'destroy'])->name('pengajuan.destroy');

    // Chat
    // Route::get('/chat/buka/{id}', [ChatController::class, 'openChat'])->name('chat.open');
    // Route::get('/chat/buka/{id}/{item_id}', [ChatController::class, 'openChat'])->name('chat.open');
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{chat}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{chat}/pesan', [ChatController::class, 'store'])->name('chat.store');
    Route::post('/chat/mulai/{item}', [ChatController::class, 'mulai'])->name('chat.mulai');
    Route::get('/chat/buka/{id}/{item_id}', [ChatController::class, 'openChat'])
    ->name('chat.open');

    // Ulasan & Konfirmasi Penerimaan
    Route::get('/ulasan/buat', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/ulasan', [ReviewController::class, 'store'])->name('reviews.store');

    // Notifikasi
    Route::get('/notifikasi', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifikasi/baca-semua', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

    // Profil
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profil', [ProfileController::class, 'destroy'])->name('profile.destroy');

});