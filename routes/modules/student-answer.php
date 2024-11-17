<?php

use App\Http\Middleware\EnsureAppKey;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StudentAnswerController as ApiController;
use App\Http\Controllers\StudentAnswerController as WebController;
use App\Http\Middleware\ViewShare;

# API
Route::prefix('api/v1')->as('api.')->middleware(['api', EnsureAppKey::class, 'auth:api'])->group(function () {
    Route::apiResource('student-answers', ApiController::class);
});

# WEB
Route::middleware(['web', ViewShare::class, 'auth'])->group(function () {
    Route::get('student-answers/print', [WebController::class, 'exportPrint'])->name('student-answers.print');
    Route::get('student-answers/pdf', [WebController::class, 'pdf'])->name('student-answers.pdf');
    Route::get('student-answers/csv', [WebController::class, 'csv'])->name('student-answers.csv');
    Route::get('student-answers/json', [WebController::class, 'json'])->name('student-answers.json');
    Route::get('student-answers/excel', [WebController::class, 'excel'])->name('student-answers.excel');
    Route::get('student-answers/import-excel-example', [WebController::class, 'importExcelExample'])->name('student-answers.import-excel-example');
    Route::post('student-answers/import-excel', [WebController::class, 'importExcel'])->name('student-answers.import-excel');
    Route::resource('student-answers', WebController::class);
});
