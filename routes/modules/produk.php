<?php

use App\Http\Middleware\EnsureAppKey;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProdukController as ApiController;
use App\Http\Controllers\ProdukController as WebController;
use App\Http\Middleware\ViewShare;

# API
Route::prefix('api/v1')->as('api.')->middleware(['api', EnsureAppKey::class, 'auth:api'])->group(function () {
    Route::apiResource('produks', ApiController::class);
});

# WEB
Route::middleware(['web', ViewShare::class, 'auth'])->group(function () {
    Route::get('produks/print', [WebController::class, 'exportPrint'])->name('produks.print');
    Route::get('produks/pdf', [WebController::class, 'pdf'])->name('produks.pdf');
    Route::get('produks/csv', [WebController::class, 'csv'])->name('produks.csv');
    Route::get('produks/json', [WebController::class, 'json'])->name('produks.json');
    Route::get('produks/excel', [WebController::class, 'excel'])->name('produks.excel');
    Route::get('produks/import-excel-example', [WebController::class, 'importExcelExample'])->name('produks.import-excel-example');
    Route::post('produks/import-excel', [WebController::class, 'importExcel'])->name('produks.import-excel');
    Route::resource('produks', WebController::class);
});
