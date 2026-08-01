<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExamRequest;
use App\Models\Exam;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function index()
    {
        $teacher = $this->currentTeacher();

        $exams = $teacher
            ? Exam::with(['subject', 'schoolClass'])
                ->where('teacher_id', $teacher->id)
                ->withCount('questions')
                ->latest()
                ->get()
            : collect();

        return view('exams.index', compact('exams'));
    }

    public function create()
    {
        $teacher = $this->currentTeacher();

        $assignments = $teacher
            ? $teacher->teachingAssignments()->with(['schoolClass', 'subject'])->get()
            : collect();

        return view('exams.create', compact('assignments'));
    }

    public function store(StoreExamRequest $request)
    {
        $exam = Exam::create($request->toModelData());

        return redirect()
            ->route('guru.exams.show', $exam)
            ->with('status', 'Paket ujian berhasil dibuat. Sekarang tambahkan soal-soalnya, lalu tunggu persetujuan admin.');
    }

    public function show(Exam $exam)
    {
        $this->authorize('manage', $exam);

        $exam->load(['questions', 'subject', 'schoolClass']);

        return view('exams.show', compact('exam'));
    }

    public function destroy(Exam $exam)
    {
        $this->authorize('manage', $exam);

        $exam->delete();

        return redirect()
            ->route('guru.exams.index')
            ->with('status', 'Paket ujian berhasil dihapus.');
    }

    public function resubmit(Exam $exam)
    {
        $this->authorize('manage', $exam);

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

    private function currentTeacher()
    {
        return Auth::user()->teacher;
    }
}