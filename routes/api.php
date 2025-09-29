<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MappingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Middleware 'auth:sanctum' akan memastikan bahwa hanya user yang sudah login
// (dan memiliki token yang valid atau sesi cookie) yang bisa mengakses route di dalam grup ini.
Route::middleware('auth:sanctum')->group(function () {
    
    // Route untuk Vue mengambil daftar mapping
    Route::get('/mappings', [MappingController::class, 'getMappings']);

    // Route untuk Vue mengirim file upload
    Route::post('/upload', [MappingController::class, 'handleUpload']);

});
