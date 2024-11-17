<?php

use App\Http\Middleware\EnsureAppKey;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CompetenceCourseController as ApiController;
use App\Http\Controllers\CompetenceCourseController as WebController;
use App\Http\Middleware\ViewShare;

# API
Route::prefix('api/v1')->as('api.')->middleware(['api', EnsureAppKey::class, 'auth:api'])->group(function () {
    Route::apiResource('competence-courses', ApiController::class);
});

# WEB
Route::middleware(['web', ViewShare::class, 'auth'])->group(function () {
    Route::get('competence-courses/print', [WebController::class, 'exportPrint'])->name('competence-courses.print');
    Route::get('competence-courses/pdf', [WebController::class, 'pdf'])->name('competence-courses.pdf');
    Route::get('competence-courses/csv', [WebController::class, 'csv'])->name('competence-courses.csv');
    Route::get('competence-courses/json', [WebController::class, 'json'])->name('competence-courses.json');
    Route::get('competence-courses/excel', [WebController::class, 'excel'])->name('competence-courses.excel');
    Route::get('competence-courses/import-excel-example', [WebController::class, 'importExcelExample'])->name('competence-courses.import-excel-example');
    Route::post('competence-courses/import-excel', [WebController::class, 'importExcel'])->name('competence-courses.import-excel');
    Route::resource('competence-courses', WebController::class);
});
