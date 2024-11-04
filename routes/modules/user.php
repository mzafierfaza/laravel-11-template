<?php

use App\Http\Middleware\EnsureAppKey;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsersController as ApiController;
use App\Http\Controllers\UsersController as WebController;
use App\Http\Middleware\ViewShare;

# API
Route::prefix('api/v1')->as('api.')->middleware(['api', EnsureAppKey::class, 'auth:api'])->group(function () {
    Route::apiResource('users', ApiController::class);
});

# WEB
Route::middleware(['web', ViewShare::class, 'auth'])->group(function () {
    Route::get('users/print', [WebController::class, 'exportPrint'])->name('users.print');
    Route::get('users/pdf', [WebController::class, 'pdf'])->name('users.pdf');
    Route::get('users/csv', [WebController::class, 'csv'])->name('users.csv');
    Route::get('users/json', [WebController::class, 'json'])->name('users.json');
    Route::get('users/excel', [WebController::class, 'excel'])->name('users.excel');
    Route::get('users/import-excel-example', [WebController::class, 'importExcelExample'])->name('users.import-excel-example');
    Route::post('users/import-excel', [WebController::class, 'importExcel'])->name('users.import-excel');
    Route::resource('users', WebController::class);
});
