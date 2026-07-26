<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return $this->adminDashboard();
        }

        $stats = [];

        if ($user->hasRole('wali_kelas')) {
            $stats['wali_kelas'] = $this->waliKelasStats($user);
        }

        if ($user->hasRole('guru')) {
            $stats['guru'] = $this->guruStats($user);
        }

        if ($user->hasRole('siswa')) {
            $stats['siswa'] = $this->siswaStats($user);
        }

        if ($user->hasRole('orang_tua')) {
            $stats['orang_tua'] = $this->orangTuaStats($user);
        }

        return view('dashboard', compact('stats'));
    }

    private function adminDashboard()
    {
        $stats = [
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'total_classes' => SchoolClass::count(),
            'active_school_year' => SchoolYear::where('is_active', true)->first(),
        ];

        return view('dashboard', compact('stats'));
    }

    private function waliKelasStats($user): array
    {
        $teacher = $user->teacher;
        $class = $teacher
            ? SchoolClass::where('homeroom_teacher_id', $teacher->id)->first()
            : null;

        return [
            'class' => $class,
            'total_students' => $class ? $class->students()->count() : 0,
        ];
    }

    private function guruStats($user): array
    {
        $teacher = $user->teacher;

        return [
            'teacher' => $teacher,
            'total_classes_taught' => $teacher
                ? DB::table('class_subject_teacher')->where('teacher_id', $teacher->id)->distinct('class_id')->count('class_id')
                : 0,
            'total_subjects' => $teacher
                ? DB::table('class_subject_teacher')->where('teacher_id', $teacher->id)->distinct('subject_id')->count('subject_id')
                : 0,
        ];
    }

    private function siswaStats($user): array
    {
        $student = $user->student;

        return [
            'student' => $student,
            'class' => $student?->schoolClass,
        ];
    }

    private function orangTuaStats($user): array
    {
        $guardian = $user->guardian;
        $children = $guardian ? $guardian->students()->with('schoolClass')->get() : collect();

        return [
            'children' => $children,
        ];
    }
}