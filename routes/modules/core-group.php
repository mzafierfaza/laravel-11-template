<?php

use App\Http\Middleware\EnsureAppKey;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CoreGroupController as ApiController;
use App\Http\Controllers\CoreGroupController as WebController;
use App\Http\Middleware\ViewShare;

# API
Route::prefix('api/v1')->as('api.')->middleware(['api', EnsureAppKey::class, 'auth:api'])->group(function () {
    Route::apiResource('core-groups', ApiController::class);
});

# WEB
Route::middleware(['web', ViewShare::class, 'auth'])->group(function () {
    Route::get('core-groups/print', [WebController::class, 'exportPrint'])->name('core-groups.print');
    Route::get('core-groups/pdf', [WebController::class, 'pdf'])->name('core-groups.pdf');
    Route::get('core-groups/csv', [WebController::class, 'csv'])->name('core-groups.csv');
    Route::get('core-groups/json', [WebController::class, 'json'])->name('core-groups.json');
    Route::get('core-groups/excel', [WebController::class, 'excel'])->name('core-groups.excel');
    Route::get('core-groups/import-excel-example', [WebController::class, 'importExcelExample'])->name('core-groups.import-excel-example');
    Route::post('core-groups/import-excel', [WebController::class, 'importExcel'])->name('core-groups.import-excel');
    Route::resource('core-groups', WebController::class);
});
