<?php

use App\Http\Middleware\EnsureAppKey;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\QuizAttemptController as ApiController;
use App\Http\Controllers\QuizAttemptController as WebController;
use App\Http\Middleware\ViewShare;

# API
Route::prefix('api/v1')->as('api.')->middleware(['api', EnsureAppKey::class, 'auth:api'])->group(function () {
    Route::apiResource('quiz-attempts', ApiController::class);
});

# WEB
Route::middleware(['web', ViewShare::class, 'auth'])->group(function () {
    Route::get('quiz-attempts/print', [WebController::class, 'exportPrint'])->name('quiz-attempts.print');
    Route::get('quiz-attempts/pdf', [WebController::class, 'pdf'])->name('quiz-attempts.pdf');
    Route::get('quiz-attempts/csv', [WebController::class, 'csv'])->name('quiz-attempts.csv');
    Route::get('quiz-attempts/json', [WebController::class, 'json'])->name('quiz-attempts.json');
    Route::get('quiz-attempts/excel', [WebController::class, 'excel'])->name('quiz-attempts.excel');
    Route::get('quiz-attempts/import-excel-example', [WebController::class, 'importExcelExample'])->name('quiz-attempts.import-excel-example');
    Route::post('quiz-attempts/import-excel', [WebController::class, 'importExcel'])->name('quiz-attempts.import-excel');
    Route::resource('quiz-attempts', WebController::class);
});
