<?php

use App\Http\Middleware\EnsureAppKey;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\QuestionController as ApiController;
use App\Http\Controllers\QuestionController as WebController;
use App\Http\Middleware\ViewShare;

# API
Route::prefix('api/v1')->as('api.')->middleware(['api', EnsureAppKey::class, 'auth:api'])->group(function () {
    Route::apiResource('questions', ApiController::class);
});

# WEB
Route::middleware(['web', ViewShare::class, 'auth'])->group(function () {
    Route::get('questions/print', [WebController::class, 'exportPrint'])->name('questions.print');
    Route::get('questions/pdf', [WebController::class, 'pdf'])->name('questions.pdf');
    Route::get('questions/csv', [WebController::class, 'csv'])->name('questions.csv');
    Route::get('questions/json', [WebController::class, 'json'])->name('questions.json');
    Route::get('questions/excel', [WebController::class, 'excel'])->name('questions.excel');
    Route::get('questions/import-excel-example', [WebController::class, 'importExcelExample'])->name('questions.import-excel-example');
    Route::post('questions/import-excel', [WebController::class, 'importExcel'])->name('questions.import-excel');
    Route::resource('questions', WebController::class);
});
