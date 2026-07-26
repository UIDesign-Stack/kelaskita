<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\SubstituteTeacher;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubstituteTeacherController extends Controller
{
    public function index()
    {
        $logs = SubstituteTeacher::with(['originalTeacher.user', 'substituteTeacher.user', 'schoolClass', 'subject'])
            ->latest('date')
            ->get();

        return view('substitute-teachers.index', compact('logs'));
    }

    public function create()
    {
        $teachers = Teacher::with('user')->get();
        $classes = SchoolClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('substitute-teachers.create', compact('teachers', 'classes', 'subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'original_teacher_id' => ['required', 'exists:teachers,id'],
            'substitute_teacher_id' => ['required', 'exists:teachers,id', 'different:original_teacher_id'],
            'class_id' => ['required', 'exists:classes,id'],
            'subject_id' => ['nullable', 'exists:subjects,id'],
            'date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        SubstituteTeacher::create([
            ...$validated,
            'recorded_by' => Auth::id(),
        ]);

        return redirect()
            ->route('presensi.substitute-teachers.index')
            ->with('status', 'Log guru pengganti berhasil dicatat.');
    }

    public function destroy(SubstituteTeacher $substituteTeacher)
    {
        $substituteTeacher->delete();

        return redirect()
            ->route('presensi.substitute-teachers.index')
            ->with('status', 'Log guru pengganti berhasil dihapus.');
    }
}