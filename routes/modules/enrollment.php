<?php

use App\Http\Middleware\EnsureAppKey;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EnrollmentController as ApiController;
use App\Http\Controllers\EnrollmentController as WebController;
use App\Http\Middleware\ViewShare;

# API
Route::prefix('api/v1')->as('api.')->middleware(['api', EnsureAppKey::class, 'auth:api'])->group(function () {
    Route::apiResource('enrollments', ApiController::class);
});

# WEB
Route::middleware(['web', ViewShare::class, 'auth'])->group(function () {});
