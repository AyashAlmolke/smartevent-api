<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\Admin\Auth\LoginController;

// ✅ Public API routes
Route::post('/admin/login', [LoginController::class, 'login']);

// ✅ Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/scan', [ScanController::class, 'scan']);
    Route::post('/admin/logout', [LoginController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

// ✅ Health check
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});
