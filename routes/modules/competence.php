<?php

use App\Http\Middleware\EnsureAppKey;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CompetenceController as ApiController;
use App\Http\Controllers\CompetenceController as WebController;
use App\Http\Middleware\ViewShare;

# API
Route::prefix('api/v1')->as('api.')->middleware(['api', EnsureAppKey::class, 'auth:api'])->group(function () {
    Route::apiResource('competences', ApiController::class);
});

# WEB
Route::middleware(['web', ViewShare::class, 'auth'])->group(function () {
    Route::get('competences/print', [WebController::class, 'exportPrint'])->name('competences.print');
    Route::get('competences/pdf', [WebController::class, 'pdf'])->name('competences.pdf');
    Route::get('competences/csv', [WebController::class, 'csv'])->name('competences.csv');
    Route::get('competences/json', [WebController::class, 'json'])->name('competences.json');
    Route::get('competences/excel', [WebController::class, 'excel'])->name('competences.excel');
    Route::get('competences/import-excel-example', [WebController::class, 'importExcelExample'])->name('competences.import-excel-example');
    Route::post('competences/import-excel', [WebController::class, 'importExcel'])->name('competences.import-excel');
    Route::resource('competences', WebController::class);
});
