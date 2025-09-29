<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MappingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Middleware 'auth:sanctum' adalah cara yang benar untuk melindungi API
// yang diakses oleh frontend SPA (seperti Vue) dalam proyek yang sama.
Route::middleware('auth:sanctum')->group(function () {
    
    // Get list of mappings
    Route::get('/mappings', [MappingController::class, 'index']);

    // Create new mapping
    Route::post('/mappings', [MappingController::class, 'store']);

    // Get single mapping
    Route::get('/mappings/{id}', [MappingController::class, 'show']);

    // Update mapping
    Route::put('/mappings/{id}', [MappingController::class, 'update']);

    // Delete mapping
    Route::delete('/mappings/{id}', [MappingController::class, 'destroy']);

    // Get user permissions
    Route::get('/user/permissions', [MappingController::class, 'getUserPermissions']);

    // Upload endpoint (placeholder for now)
    Route::post('/upload', function() {
        return response()->json(['message' => 'Upload endpoint - coming soon']);
    });

});

// Rute ini bisa dihapus jika tidak digunakan, karena sudah ada di dalam grup di atas
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');