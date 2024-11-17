<?php

use App\Http\Middleware\EnsureAppKey;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ModuleController as ApiController;
use App\Http\Controllers\ModuleController as WebController;
use App\Http\Middleware\ViewShare;

# API
Route::prefix('api/v1')->as('api.')->middleware(['api', EnsureAppKey::class, 'auth:api'])->group(function () {
    Route::apiResource('modules', ApiController::class);
});

# WEB
Route::middleware(['web', ViewShare::class, 'auth'])->group(function () {
    Route::get('modules/print', [WebController::class, 'exportPrint'])->name('modules.print');
    Route::get('modules/pdf', [WebController::class, 'pdf'])->name('modules.pdf');
    Route::get('modules/csv', [WebController::class, 'csv'])->name('modules.csv');
    Route::get('modules/json', [WebController::class, 'json'])->name('modules.json');
    Route::get('modules/excel', [WebController::class, 'excel'])->name('modules.excel');
    Route::get('modules/import-excel-example', [WebController::class, 'importExcelExample'])->name('modules.import-excel-example');
    Route::post('modules/import-excel', [WebController::class, 'importExcel'])->name('modules.import-excel');
    Route::resource('modules', WebController::class);
});
