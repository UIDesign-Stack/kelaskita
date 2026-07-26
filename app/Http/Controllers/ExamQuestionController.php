<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamQuestionController extends Controller
{
    public function create(Exam $exam)
    {
        $this->authorizeExam($exam);

        return view('exam-questions.create', compact('exam'));
    }

    public function store(Exam $exam, Request $request)
    {
        $this->authorizeExam($exam);

        $validated = $request->validate([
            'type' => ['required', 'in:pilihan_ganda,esai'],
            'question' => ['required', 'string'],
            'option_a' => ['nullable', 'required_if:type,pilihan_ganda', 'string', 'max:255'],
            'option_b' => ['nullable', 'required_if:type,pilihan_ganda', 'string', 'max:255'],
            'option_c' => ['nullable', 'string', 'max:255'],
            'option_d' => ['nullable', 'string', 'max:255'],
            'correct_answer' => ['nullable', 'required_if:type,pilihan_ganda', 'in:A,B,C,D'],
            'weight' => ['required', 'integer', 'min:1', 'max:100'],
        ]);

        $exam->questions()->create($validated);

        return redirect()
            ->route('guru.exams.show', $exam)
            ->with('status', 'Soal berhasil ditambahkan.');
    }

    public function destroy(Exam $exam, ExamQuestion $question)
    {
        $this->authorizeExam($exam);

        $question->delete();

        return redirect()
            ->route('guru.exams.show', $exam)
            ->with('status', 'Soal berhasil dihapus.');
    }

    private function authorizeExam(Exam $exam): void
    {
        $teacher = Auth::user()->teacher;

        abort_if(!$teacher || $exam->teacher_id !== $teacher->id, 403, 'Anda tidak memiliki akses ke ujian ini.');
    }
}