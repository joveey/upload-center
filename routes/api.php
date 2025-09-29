<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MappingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// PENTING: Gunakan web middleware untuk session-based authentication
Route::middleware(['web', 'auth'])->group(function () {
    
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