<?php

use App\Http\Controllers\Api\ResumeController;
use Illuminate\Support\Facades\Route;

Route::get('resumes', [ResumeController::class, 'index']);
Route::get('resumes/{resume}', [ResumeController::class, 'show']);

Route::middleware('local.write')->group(function (): void {
    Route::post('resumes', [ResumeController::class, 'store']);
    Route::put('resumes/{resume}', [ResumeController::class, 'update']);
    Route::patch('resumes/{resume}', [ResumeController::class, 'update']);
    Route::delete('resumes/{resume}', [ResumeController::class, 'destroy']);
});
