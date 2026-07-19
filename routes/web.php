<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\GradeController;

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
});

Route::prefix('akademik')
    ->name('akademik.')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->group(function () {
        Route::get('grades', [GradeController::class, 'index'])->name('grades.index');
});
 

require __DIR__.'/auth.php';