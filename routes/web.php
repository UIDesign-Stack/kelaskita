<?php

use App\Http\Controllers\TeachingAssignmentController;
use App\Http\Controllers\GradeInputController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\TeacherMaterialController;
use App\Http\Controllers\ReportCardController;


use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
});
 
Route::prefix('data-master')
    ->name('data-master.')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->group(function () {
        Route::resource('students', StudentController::class)->only(['index', 'create','store','show', 'edit', 'update', 'destroy']);
        Route::resource('teachers', TeacherController::class)->only(['index', 'create','store','show','edit', 'update','destroy']);
        Route::resource('classes', ClassController::class)->only(['index', 'create','store','show', 'edit', 'update', 'destroy']);
        Route::resource('subjects', SubjectController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::resource('school-years', SchoolYearController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
        Route::post('classes/{class}/teaching-assignments', [TeachingAssignmentController::class, 'store'])->name('classes.teaching-assignments.store');
        Route::delete('teaching-assignments/{assignment}', [TeachingAssignmentController::class, 'destroy'])->name('teaching-assignments.destroy');        
});

Route::prefix('akademik')
    ->name('akademik.')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->group(function () {
        Route::get('grades', [GradeController::class, 'index'])->name('grades.index');
        Route::get('report-cards', [ReportCardController::class, 'index'])->name('report-cards.index');
        Route::get('report-cards/{student}', [ReportCardController::class, 'show'])->name('report-cards.show');
        Route::post('report-cards/{reportCard}/finalize', [ReportCardController::class, 'finalize'])->name('report-cards.finalize');
        Route::get('materials', [MaterialController::class, 'index'])->name('materials.index');
    });

Route::prefix('guru')
    ->name('guru.')
    ->middleware(['auth', 'verified', 'role:guru'])
    ->group(function () {
        Route::get('nilai', [GradeInputController::class, 'index'])->name('grade-input.index');
        Route::get('nilai/{assignment}', [GradeInputController::class, 'create'])->name('grade-input.create');
        Route::post('nilai/{assignment}', [GradeInputController::class, 'store'])->name('grade-input.store');
        Route::get('materials', [TeacherMaterialController::class, 'index'])->name('materials.index');
        Route::get('materials/create', [TeacherMaterialController::class, 'create'])->name('materials.create');
        Route::post('materials', [TeacherMaterialController::class, 'store'])->name('materials.store');
    });

 

require __DIR__.'/auth.php';