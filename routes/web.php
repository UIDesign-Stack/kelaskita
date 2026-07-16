<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ProfileController;
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
        Route::resource('students', StudentController::class)->only(['index', 'create','store']);
        Route::resource('teachers', TeacherController::class)->only(['index', 'create','store']);
    });

require __DIR__.'/auth.php';