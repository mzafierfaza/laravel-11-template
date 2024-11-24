<?php

use App\Http\Middleware\EnsureAppKey;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\QuizController as ApiController;
use App\Http\Controllers\QuizController as WebController;
use App\Http\Middleware\ViewShare;

# API
Route::prefix('api/v1')->as('api.')->middleware(['api', EnsureAppKey::class, 'auth:api'])->group(function () {
    Route::apiResource('quizzes', ApiController::class);
});

# WEB
// Route::middleware(['web', ViewShare::class, 'auth'])->group(function () {
//     Route::get('quizzes/print', [WebController::class, 'exportPrint'])->name('quizzes.print');
//     Route::get('quizzes/pdf', [WebController::class, 'pdf'])->name('quizzes.pdf');
//     Route::get('quizzes/csv', [WebController::class, 'csv'])->name('quizzes.csv');
//     Route::get('quizzes/json', [WebController::class, 'json'])->name('quizzes.json');
//     Route::get('quizzes/excel', [WebController::class, 'excel'])->name('quizzes.excel');
//     Route::get('quizzes/import-excel-example', [WebController::class, 'importExcelExample'])->name('quizzes.import-excel-example');
//     Route::post('quizzes/import-excel', [WebController::class, 'importExcel'])->name('quizzes.import-excel');
//     Route::resource(name: 'quizzes', WebController::class);
// });
