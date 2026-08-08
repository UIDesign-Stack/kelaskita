<?php

namespace App\Http\Controllers;

use App\Models\CounselingNote;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CounselingNoteController extends Controller
{
    public function index(Request $request)
    {
        $classes = SchoolClass::orderBy('name')->get();

        $query = CounselingNote::with(['student.schoolClass', 'recordedBy']);

        if ($request->filled('class_id')) {
            $query->whereHas('student', fn ($q) => $q->where('class_id', $request->class_id));
        }

        if ($request->filled('student_search')) {
            $query->whereHas('student', fn ($q) => $q->where('name', 'like', '%' . $request->student_search . '%'));
        }

        $notes = $query->latest('date')->get();

        return view('counseling-notes.index', compact('classes', 'notes'));
    }

    public function create()
    {
        $students = Student::with('schoolClass')->orderBy('name')->get();

        return view('counseling-notes.create', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'date' => ['required', 'date'],
            'note' => ['required', 'string', 'max:2000'],
            'is_confidential' => ['nullable', 'boolean'],
        ]);

        CounselingNote::create([
            'student_id' => $validated['student_id'],
            'date' => $validated['date'],
            'note' => $validated['note'],
            'is_confidential' => $request->boolean('is_confidential', true),
            'recorded_by' => Auth::id(),
        ]);

        return redirect()
            ->route('perilaku.counseling-notes.index')
            ->with('status', 'Catatan BK berhasil disimpan.');
    }

    public function destroy(CounselingNote $counselingNote)
    {
        $counselingNote->delete();

        return redirect()
            ->route('perilaku.counseling-notes.index')
            ->with('status', 'Catatan BK berhasil dihapus.');
    }
}