<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TeacherController;
 use App\Http\Controllers\GradeController;
use App\Http\Controllers\AdvancedGradeController;
use App\Http\Controllers\ResultsPdfController;
use App\Http\Controllers\DashboardController;

Route::get('/results/pdf', [ResultsPdfController::class, 'generate'])->name('results.pdf');

Route::get('/advanced-grades', [AdvancedGradeController::class, 'index'])
    ->middleware(['auth'])
    ->name('advanced-grades.index');


Route::get('/', [DashboardController::class, 'index'])->name('welcome');



Route::get('/dashboard', [TeacherController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); 
    Route::post('/grades/store', [GradeController::class, 'store'])->name('grades.store');

});


// // Advanced Grades Routes
// Route::get('/advanced-grades', [AdvancedGradeController::class, 'index'])->name('advanced-grades.index');
Route::post('/advanced-grades', [AdvancedGradeController::class, 'store'])->name('advanced-grades.store');
// Route::get('/advanced-grades/debug', [AdvancedGradeController::class, 'debug'])->name('advanced-grades.debug');

require __DIR__.'/auth.php';
