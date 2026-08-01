<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExamQuestionRequest;
use App\Models\Exam;
use App\Models\ExamQuestion;

class ExamQuestionController extends Controller
{
    public function create(Exam $exam)
    {
        $this->authorize('manage', $exam);

        return view('exam-questions.create', compact('exam'));
    }

    public function store(Exam $exam, StoreExamQuestionRequest $request)
    {
        $this->authorize('manage', $exam);

        $exam->questions()->create($request->validated());

        return redirect()
            ->route('guru.exams.show', $exam)
            ->with('status', 'Soal berhasil ditambahkan.');
    }

    public function destroy(Exam $exam, ExamQuestion $question)
    {
        $this->authorize('manage', $exam);

    
        abort_unless($question->exam_id === $exam->id, 404);

        $question->delete();

        return redirect()
            ->route('guru.exams.show', $exam)
            ->with('status', 'Soal berhasil dihapus.');
    }
}