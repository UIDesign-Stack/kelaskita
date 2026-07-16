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

        if ($user->hasRole('wali_kelas')) {
            return $this->waliKelasDashboard($user);
        }

        if ($user->hasRole('guru')) {
            return $this->guruDashboard($user);
        }

        if ($user->hasRole('siswa')) {
            return $this->siswaDashboard($user);
        }

        if ($user->hasRole('orang_tua')) {
            return $this->orangTuaDashboard($user);
        }

        return view('dashboard');
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

    private function waliKelasDashboard($user)
    {
        $teacher = $user->teacher;
        $class = $teacher
            ? SchoolClass::where('homeroom_teacher_id', $teacher->id)->first()
            : null;

        $stats = [
            'class' => $class,
            'total_students' => $class ? $class->students()->count() : 0,
        ];

        return view('dashboard', compact('stats'));
    }

    private function guruDashboard($user)
    {
        $teacher = $user->teacher;

        $stats = [
            'teacher' => $teacher,
            'total_classes_taught' => $teacher
                ? DB::table('class_subject_teacher')->where('teacher_id', $teacher->id)->distinct('class_id')->count('class_id')
                : 0,
            'total_subjects' => $teacher
                ? DB::table('class_subject_teacher')->where('teacher_id', $teacher->id)->distinct('subject_id')->count('subject_id')
                : 0,
        ];

        return view('dashboard', compact('stats'));
    }

    private function siswaDashboard($user)
    {
        $student = $user->student;

        $stats = [
            'student' => $student,
            'class' => $student?->schoolClass,
        ];

        return view('dashboard', compact('stats'));
    }

    private function orangTuaDashboard($user)
    {
        $guardian = $user->guardian;
        $children = $guardian ? $guardian->students()->with('schoolClass')->get() : collect();

        $stats = [
            'children' => $children,
        ];

        return view('dashboard', compact('stats'));
    }
}