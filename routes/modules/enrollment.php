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
Route::middleware(['web', ViewShare::class, 'auth'])->group(function () {
    Route::get('enrollments/print', [WebController::class, 'exportPrint'])->name('enrollments.print');
    Route::get('enrollments/pdf', [WebController::class, 'pdf'])->name('enrollments.pdf');
    Route::get('enrollments/csv', [WebController::class, 'csv'])->name('enrollments.csv');
    Route::get('enrollments/json', [WebController::class, 'json'])->name('enrollments.json');
    Route::get('enrollments/excel', [WebController::class, 'excel'])->name('enrollments.excel');
    Route::get('enrollments/import-excel-example', [WebController::class, 'importExcelExample'])->name('enrollments.import-excel-example');
    Route::post('enrollments/import-excel', [WebController::class, 'importExcel'])->name('enrollments.import-excel');
    Route::resource('enrollments', WebController::class);
});
