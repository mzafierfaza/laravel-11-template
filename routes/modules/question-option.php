<?php

use App\Http\Middleware\EnsureAppKey;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\QuestionOptionController as ApiController;
use App\Http\Controllers\QuestionOptionController as WebController;
use App\Http\Middleware\ViewShare;

# API
Route::prefix('api/v1')->as('api.')->middleware(['api', EnsureAppKey::class, 'auth:api'])->group(function () {
    Route::apiResource('question-options', ApiController::class);
});

# WEB
Route::middleware(['web', ViewShare::class, 'auth'])->group(function () {
    Route::get('question-options/print', [WebController::class, 'exportPrint'])->name('question-options.print');
    Route::get('question-options/pdf', [WebController::class, 'pdf'])->name('question-options.pdf');
    Route::get('question-options/csv', [WebController::class, 'csv'])->name('question-options.csv');
    Route::get('question-options/json', [WebController::class, 'json'])->name('question-options.json');
    Route::get('question-options/excel', [WebController::class, 'excel'])->name('question-options.excel');
    Route::get('question-options/import-excel-example', [WebController::class, 'importExcelExample'])->name('question-options.import-excel-example');
    Route::post('question-options/import-excel', [WebController::class, 'importExcel'])->name('question-options.import-excel');
    Route::resource('question-options', WebController::class);
});
