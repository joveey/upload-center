<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MappingController;
use App\Http\Controllers\HomeController; // Tambahkan ini untuk kerapian

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Middleware 'auth' memastikan hanya user yang sudah login yang bisa mengakses halaman ini.
Route::middleware(['auth'])->group(function () {

    // Halaman utama untuk menampilkan form upload
    Route::get('/', [MappingController::class, 'showUploadForm'])->name('upload.form');

    // Menangani proses upload file.
    Route::post('/upload', [MappingController::class, 'handleUpload'])
        ->name('upload.handle')
        ->middleware('can:upload data');

    // Menampilkan halaman untuk mendaftarkan format baru.
    Route::get('/register', [MappingController::class, 'showRegisterForm'])
        ->name('register.form')
        ->middleware('can:register format');

    // Menyimpan data dari form pendaftaran format baru.
    Route::post('/register', [MappingController::class, 'storeRegisterForm'])
        ->name('register.store')
        ->middleware('can:register format');

});

// Menambahkan semua route yang dibutuhkan untuk autentikasi (login, register, logout, dll.)
Auth::routes();

// Jika pengguna mengakses /home setelah login, arahkan ke halaman utama.
Route::get('/home', [HomeController::class, 'index'])->name('home');