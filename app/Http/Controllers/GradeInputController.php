<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGradeInputRequest;
use App\Models\ClassSubjectTeacher;
use App\Models\Grade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

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
        $this->authorize('manage', $assignment);

        $assignment->load(['schoolClass.students' => fn ($q) => $q->orderBy('name'), 'subject', 'schoolYear']);

        $semester = $request->query('semester', 'ganjil');
        $type = $request->query('type', 'tugas');

        $existingGrades = Grade::where('subject_id', $assignment->subject_id)
            ->where('teacher_id', $assignment->teacher_id)
            ->where('school_year_id', $assignment->school_year_id)
            ->where('semester', $semester)
            ->where('type', $type)
            ->whereIn('student_id', $assignment->schoolClass->students->pluck('id'))
            ->pluck('score', 'student_id');

        return view('grade-input.create', compact('assignment', 'semester', 'type', 'existingGrades'));
    }

    public function store(ClassSubjectTeacher $assignment, StoreGradeInputRequest $request)
    {
        $validated = $request->validated();

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
}