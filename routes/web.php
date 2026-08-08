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
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\TeacherDocumentController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\WalasAttendanceController;
use App\Http\Controllers\GuruAttendanceController;
use App\Http\Controllers\TeacherAttendanceController;
use App\Http\Controllers\SubstituteTeacherController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamQuestionController;
use App\Http\Controllers\ExamReviewController;
use App\Http\Controllers\StudentExamController;
use App\Http\Controllers\GuruExamResultController;
use App\Http\Controllers\ExamAnalysisController;
use App\Http\Controllers\ViolationController;
use App\Http\Controllers\WalasViolationController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\WalasAchievementController;
use App\Http\Controllers\WalasCommunicationController;
use App\Http\Controllers\ParentCommunicationController;
use App\Http\Controllers\CounselingNoteController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
    ->name('profile.edit');
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
        Route::resource('document-types', DocumentTypeController::class)->only(['index', 'store', 'update', 'destroy']);
});

Route::prefix('akademik')
    ->name('akademik.')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->group(function () {
        Route::get('grades', [GradeController::class, 'index'])
        ->name('grades.index');
        Route::get('report-cards', [ReportCardController::class, 'index'])->name('report-cards.index');
        Route::get('report-cards/{student}', [ReportCardController::class, 'show'])->name('report-cards.show');
        Route::post('report-cards/{reportCard}/finalize', [ReportCardController::class, 'finalize'])->name('report-cards.finalize');
        Route::get('materials', [MaterialController::class, 'index'])->name('materials.index');
        Route::get('documents', [DocumentController::class, 'index'])->name('documents.index');
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
        Route::get('documents', [TeacherDocumentController::class, 'index'])->name('documents.index');
        Route::get('documents/create', [TeacherDocumentController::class, 'create'])->name('documents.create');
        Route::post('documents', [TeacherDocumentController::class, 'store'])->name('documents.store');
        Route::get('attendance', [GuruAttendanceController::class, 'index'])->name('attendance.index');
        Route::get('attendance/{assignment}', [GuruAttendanceController::class, 'create'])->name('attendance.create');
        Route::post('attendance/{assignment}', [GuruAttendanceController::class, 'store'])->name('attendance.store');
        Route::resource('exams', ExamController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
        Route::get('exams/{exam}/questions/create', [ExamQuestionController::class, 'create'])->name('exams.questions.create');
        Route::post('exams/{exam}/questions', [ExamQuestionController::class, 'store'])->name('exams.questions.store');
        Route::delete('exams/{exam}/questions/{question}', [ExamQuestionController::class, 'destroy'])->name('exams.questions.destroy');
        Route::post('exams/{exam}/resubmit', [ExamController::class, 'resubmit'])->name('exams.resubmit');
        Route::get('exams/{exam}/results', [GuruExamResultController::class, 'index'])->name('exams.results.index');
        Route::get('exam-results/{result}/grade', [GuruExamResultController::class, 'grade'])->name('exams.results.grade');
        Route::post('exam-results/{result}/grade', [GuruExamResultController::class, 'storeGrade'])->name('exams.results.store-grade');
        Route::get('exams/{exam}/analysis', [ExamAnalysisController::class, 'show'])->name('exams.analysis');
    });
Route::prefix('presensi')
    ->name('presensi.')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->group(function () {
    Route::get('attendances', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::get('teacher-attendances', [TeacherAttendanceController::class, 'index'])->name('teacher-attendances.index');
    Route::get('teacher-attendances/create', [TeacherAttendanceController::class, 'create'])->name('teacher-attendances.create');
    Route::post('teacher-attendances', [TeacherAttendanceController::class, 'store'])->name('teacher-attendances.store');
    Route::resource('substitute-teachers', SubstituteTeacherController::class)->only(['index', 'create', 'store', 'destroy']);
});
Route::prefix('wali-kelas')
    ->name('wali-kelas.')
    ->middleware(['auth', 'verified', 'role:wali_kelas'])
    ->group(function () {
        Route::get('attendance', [WalasAttendanceController::class, 'index'])->name('attendance.index');
        Route::post('attendance', [WalasAttendanceController::class, 'store'])->name('attendance.store');
        Route::get('attendance/recap', [WalasAttendanceController::class, 'recap'])->name('attendance.recap');
        Route::resource('violations', WalasViolationController::class)->only(['index', 'create', 'store', 'destroy']);
        Route::resource('achievements', WalasAchievementController::class)->only(['index', 'create', 'store', 'destroy']);
        Route::resource('communication-logs', WalasCommunicationController::class)->only(['index', 'create', 'store']);
});

 Route::prefix('ujian')
    ->name('ujian.')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->group(function () {
        Route::get('exam-review', [ExamReviewController::class, 'index'])->name('exam-review.index');
        Route::get('exam-review/{exam}', [ExamReviewController::class, 'show'])->name('exam-review.show');
        Route::post('exam-review/{exam}/approve', [ExamReviewController::class, 'approve'])->name('exam-review.approve');
        Route::post('exam-review/{exam}/reject', [ExamReviewController::class, 'reject'])->name('exam-review.reject');
    });

Route::prefix('siswa')
->name('siswa.')
->middleware(['auth', 'verified', 'role:siswa'])
->group(function () {
    Route::get('exams', [StudentExamController::class, 'index'])->name('exams.index');
    Route::post('exams/{exam}/start', [StudentExamController::class, 'start'])->name('exams.start');
    Route::get('exam-results/{result}/take', [StudentExamController::class, 'take'])->name('exams.take');
    Route::post('exam-results/{result}/submit', [StudentExamController::class, 'submit'])->name('exams.submit');
    Route::get('exam-results/{result}', [StudentExamController::class, 'result'])->name('exams.result');
});

Route::prefix('perilaku')
    ->name('perilaku.')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->group(function () {
        Route::get('violations', [ViolationController::class, 'index'])->name('violations.index');
        Route::get('achievements', [AchievementController::class, 'index'])->name('achievements.index');
        Route::resource('counseling-notes', CounselingNoteController::class)->only(['index', 'create', 'store', 'destroy']);
});


Route::prefix('orang-tua')
    ->name('orang-tua.')
    ->middleware(['auth', 'verified', 'role:orang_tua'])
    ->group(function () {
        Route::get('communication-logs', [ParentCommunicationController::class, 'index'])->name('communication-logs.index');
        Route::post('communication-logs/{log}/reply', [ParentCommunicationController::class, 'reply'])->name('communication-logs.reply');
});

require __DIR__.'/auth.php';