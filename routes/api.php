<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MappingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// PERBAIKAN: Gunakan web middleware untuk SPA, bukan auth:sanctum
Route::middleware(['web', 'auth'])->group(function () {
    
    // Route untuk Vue mengambil daftar mapping
    Route::get('/mappings', [MappingController::class, 'index']);

    // Route untuk membuat mapping baru
    Route::post('/mappings', [MappingController::class, 'store']);

    // Route untuk Vue mengirim file upload (belum ada, perlu dibuat)
    Route::post('/upload', function() {
        return response()->json(['message' => 'Upload endpoint - coming soon']);
    });

});