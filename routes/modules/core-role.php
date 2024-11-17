<?php

use App\Http\Middleware\EnsureAppKey;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CoreRoleController as ApiController;
use App\Http\Controllers\CoreRoleController as WebController;
use App\Http\Middleware\ViewShare;

# API
Route::prefix('api/v1')->as('api.')->middleware(['api', EnsureAppKey::class, 'auth:api'])->group(function () {
    Route::apiResource('core-roles', ApiController::class);
});

# WEB
Route::middleware(['web', ViewShare::class, 'auth'])->group(function () {
    Route::get('core-roles/print', [WebController::class, 'exportPrint'])->name('core-roles.print');
    Route::get('core-roles/pdf', [WebController::class, 'pdf'])->name('core-roles.pdf');
    Route::get('core-roles/csv', [WebController::class, 'csv'])->name('core-roles.csv');
    Route::get('core-roles/json', [WebController::class, 'json'])->name('core-roles.json');
    Route::get('core-roles/excel', [WebController::class, 'excel'])->name('core-roles.excel');
    Route::get('core-roles/import-excel-example', [WebController::class, 'importExcelExample'])->name('core-roles.import-excel-example');
    Route::post('core-roles/import-excel', [WebController::class, 'importExcel'])->name('core-roles.import-excel');
    Route::resource('core-roles', WebController::class);
});
