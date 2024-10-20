<?php

use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Support\Facades\Route;


// Authentication Endpoints
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/register-admin', [AuthController::class, 'registerAdmin'])->name('auth.register-admin');
    Route::post('/register-alumni', [AuthController::class, 'registerAlumni'])->name('auth.register-alumni');

    Route::middleware(['auth:api'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    });
});

