<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MappingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rute untuk halaman selamat datang (welcome page) untuk tamu (belum login)
Route::get('/', function () {
    // Jika sudah login, redirect ke /home, jika belum, tampilkan welcome page.
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return view('welcome');
});

// Menambahkan semua rute yang dibutuhkan untuk autentikasi (login, register, logout, dll.)
Auth::routes();

// --- GRUP UNTUK PENGGUNA YANG SUDAH LOGIN ---
Route::middleware(['auth'])->group(function () {

    // Ini akan menjadi halaman utama setelah login
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Menampilkan halaman untuk mendaftarkan format baru.
    Route::get('/register-format', [MappingController::class, 'showRegisterForm'])
        ->name('register.form')
        ->middleware('can:register format');

    // Menyimpan data dari form pendaftaran format baru.
    Route::post('/register-format', [MappingController::class, 'storeRegisterForm'])
        ->name('register.store')
        ->middleware('can:register format');

    // Anda bisa menambahkan rute lain yang memerlukan login di sini...
});