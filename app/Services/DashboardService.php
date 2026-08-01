<?php

namespace App\Services;

use App\Models\SchoolClass;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Mapping role => method builder.
     * Tambah role baru cukup tambah entry di sini.
     */
    private array $roleBuilders = [
        'wali_kelas' => 'waliKelasStats',
        'guru' => 'guruStats',
        'siswa' => 'siswaStats',
        'orang_tua' => 'orangTuaStats',
    ];

    /**
     * Bangun data stats sesuai role yang dimiliki user.
     * Admin selalu mendapat stats global, role lain digabung
     * (satu user bisa punya beberapa role sekaligus).
     */
    public function build(User $user): array
    {
        if ($user->hasRole('admin')) {
            return ['admin' => $this->adminStats()];
        }

        $stats = [];

        foreach ($this->roleBuilders as $role => $method) {
            if ($user->hasRole($role)) {
                $stats[$role] = $this->{$method}($user);
            }
        }

        return $stats;
    }

    private function adminStats(): array
    {
        return [
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'total_classes' => SchoolClass::count(),
            'active_school_year' => SchoolYear::where('is_active', true)->first(),
        ];
    }

    private function waliKelasStats(User $user): array
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

    private function guruStats(User $user): array
    {
        $teacher = $user->teacher;

        if (! $teacher) {
            return [
                'teacher' => null,
                'total_classes_taught' => 0,
                'total_subjects' => 0,
            ];
        }

        // 1 query untuk ambil kedua angka, dibanding 2 query terpisah
        $counts = DB::table('class_subject_teacher')
            ->where('teacher_id', $teacher->id)
            ->selectRaw('COUNT(DISTINCT class_id) as total_classes, COUNT(DISTINCT subject_id) as total_subjects')
            ->first();

        return [
            'teacher' => $teacher,
            'total_classes_taught' => $counts->total_classes ?? 0,
            'total_subjects' => $counts->total_subjects ?? 0,
        ];
    }

    private function siswaStats(User $user): array
    {
        $student = $user->student;

        return [
            'student' => $student,
            'class' => $student?->schoolClass,
        ];
    }

    private function orangTuaStats(User $user): array
    {
        $guardian = $user->guardian;
        $children = $guardian ? $guardian->students()->with('schoolClass')->get() : collect();

        return [
            'children' => $children,
        ];
    }
}