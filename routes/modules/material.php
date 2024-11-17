<?php

use App\Http\Middleware\EnsureAppKey;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MaterialController as ApiController;
use App\Http\Controllers\MaterialController as WebController;
use App\Http\Middleware\ViewShare;

# API
Route::prefix('api/v1')->as('api.')->middleware(['api', EnsureAppKey::class, 'auth:api'])->group(function () {
    Route::apiResource('materials', ApiController::class);
});

# WEB
Route::middleware(['web', ViewShare::class, 'auth'])->group(function () {
    Route::get('materials/print', [WebController::class, 'exportPrint'])->name('materials.print');
    Route::get('materials/pdf', [WebController::class, 'pdf'])->name('materials.pdf');
    Route::get('materials/csv', [WebController::class, 'csv'])->name('materials.csv');
    Route::get('materials/json', [WebController::class, 'json'])->name('materials.json');
    Route::get('materials/excel', [WebController::class, 'excel'])->name('materials.excel');
    Route::get('materials/import-excel-example', [WebController::class, 'importExcelExample'])->name('materials.import-excel-example');
    Route::post('materials/import-excel', [WebController::class, 'importExcel'])->name('materials.import-excel');
    Route::resource('materials', WebController::class);
});
