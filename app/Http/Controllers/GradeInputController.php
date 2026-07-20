<?php

namespace App\Http\Controllers;

use App\Models\ClassSubjectTeacher;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GradeInputController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;

        $assignments = $teacher
            ? $teacher->teachingAssignments()->with(['schoolClass', 'subject', 'schoolYear'])->get()
            : collect();

        return view('grade-input.index', compact('assignments', 'teacher'));
    }

    public function create(ClassSubjectTeacher $assignment, Request $request)
    {
        $this->authorizeAssignment($assignment);

        $assignment->load(['schoolClass.students' => fn ($q) => $q->orderBy('name'), 'subject', 'schoolYear']);

        $semester = $request->query('semester', 'ganjil');
        $type = $request->query('type', 'tugas');

        // Ambil nilai yang sudah pernah diinput untuk kombinasi semester+type ini
        $existingGrades = Grade::where('subject_id', $assignment->subject_id)
            ->where('teacher_id', $assignment->teacher_id)
            ->where('school_year_id', $assignment->school_year_id)
            ->where('semester', $semester)
            ->where('type', $type)
            ->whereIn('student_id', $assignment->schoolClass->students->pluck('id'))
            ->pluck('score', 'student_id');

        return view('grade-input.create', compact('assignment', 'semester', 'type', 'existingGrades'));
    }

    public function store(ClassSubjectTeacher $assignment, Request $request)
    {
        $this->authorizeAssignment($assignment);

        $validated = $request->validate([
            'semester' => ['required', 'in:ganjil,genap'],
            'type' => ['required', 'in:tugas,ulangan_harian,uts,uas'],
            'scores' => ['required', 'array'],
            'scores.*' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        DB::transaction(function () use ($validated, $assignment) {
            foreach ($validated['scores'] as $studentId => $score) {
                if ($score === null || $score === '') {
                    continue;
                }

                Grade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'subject_id' => $assignment->subject_id,
                        'teacher_id' => $assignment->teacher_id,
                        'school_year_id' => $assignment->school_year_id,
                        'semester' => $validated['semester'],
                        'type' => $validated['type'],
                    ],
                    ['score' => $score]
                );
            }
        });

        return redirect()
            ->route('guru.grade-input.create', [
                'assignment' => $assignment->id,
                'semester' => $validated['semester'],
                'type' => $validated['type'],
            ])
            ->with('status', 'Nilai berhasil disimpan.');
    }

    private function authorizeAssignment(ClassSubjectTeacher $assignment): void
    {
        $teacher = Auth::user()->teacher;

        abort_if(!$teacher || $assignment->teacher_id !== $teacher->id, 403, 'Anda tidak memiliki akses ke kelas/mapel ini.');
    }
}