<?php

namespace App\Http\Controllers;

use App\Models\ClassSubjectTeacher;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeachingAssignmentController extends Controller
{
    public function store(Request $request, SchoolClass $class)
    {
        $validated = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
        ]);

        $alreadyExists = ClassSubjectTeacher::where('class_id', $class->id)
            ->where('subject_id', $validated['subject_id'])
            ->where('school_year_id', $class->school_year_id)
            ->exists();

        if ($alreadyExists) {
            return back()->withErrors([
                'subject_id' => 'Mata pelajaran ini sudah punya guru pengampu di kelas ini untuk tahun ajaran yang sama.',
            ]);
        }

        ClassSubjectTeacher::create([
            'class_id' => $class->id,
            'subject_id' => $validated['subject_id'],
            'teacher_id' => $validated['teacher_id'],
            'school_year_id' => $class->school_year_id,
        ]);

        return redirect()
            ->route('data-master.classes.show', $class)
            ->with('status', 'Guru pengampu berhasil ditambahkan.');
    }

    public function destroy(ClassSubjectTeacher $assignment)
    {
        $classId = $assignment->class_id;
        $assignment->delete();

        return redirect()
            ->route('data-master.classes.show', $classId)
            ->with('status', 'Guru pengampu berhasil dihapus dari kelas ini.');
    }
}