<?php

namespace App\Http\Controllers;

use App\Models\ClassSubjectTeacher;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;

        $exams = $teacher
            ? Exam::with(['subject', 'schoolClass'])->where('teacher_id', $teacher->id)->withCount('questions')->latest()->get()
            : collect();

        return view('exams.index', compact('exams'));
    }

    public function create()
    {
        $teacher = Auth::user()->teacher;

        $assignments = $teacher
            ? $teacher->teachingAssignments()->with(['schoolClass', 'subject'])->get()
            : collect();

        return view('exams.create', compact('assignments'));
    }

    public function store(Request $request)
    {
        $teacher = Auth::user()->teacher;

        abort_if(!$teacher, 403);

        $validated = $request->validate([
            'assignment_id' => ['required', 'exists:class_subject_teacher,id'],
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:kuis,tryout,uts,uas'],
            'is_cbt' => ['nullable', 'boolean'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:300'],
        ]);

        $assignment = ClassSubjectTeacher::where('id', $validated['assignment_id'])
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $exam = Exam::create([
            'subject_id' => $assignment->subject_id,
            'teacher_id' => $teacher->id,
            'class_id' => $assignment->class_id,
            'title' => $validated['title'],
            'type' => $validated['type'],
            'is_cbt' => $request->boolean('is_cbt', true),
            'duration_minutes' => $validated['duration_minutes'],
            'status' => 'pending',
        ]);

        return redirect()
            ->route('guru.exams.show', $exam)
            ->with('status', 'Paket ujian berhasil dibuat. Sekarang tambahkan soal-soalnya, lalu tunggu persetujuan admin.');
    }

    public function show(Exam $exam)
    {
        $this->authorizeExam($exam);

        $exam->load(['questions', 'subject', 'schoolClass']);

        return view('exams.show', compact('exam'));
    }

    public function destroy(Exam $exam)
    {
        $this->authorizeExam($exam);

        $exam->delete();

        return redirect()
            ->route('guru.exams.index')
            ->with('status', 'Paket ujian berhasil dihapus.');
    }

    public function resubmit(Exam $exam)
    {
        $this->authorizeExam($exam);

        $exam->update([
            'status' => 'pending',
            'rejection_reason' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
        ]);

        return redirect()
            ->route('guru.exams.show', $exam)
            ->with('status', 'Paket ujian berhasil diajukan ulang untuk direview admin.');
    }

    private function authorizeExam(Exam $exam): void
    {
        $teacher = Auth::user()->teacher;

        abort_if(!$teacher || $exam->teacher_id !== $teacher->id, 403, 'Anda tidak memiliki akses ke ujian ini.');
    }
}