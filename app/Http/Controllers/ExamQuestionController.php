<?php

namespace App\Http\Controllers;


use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Http\Requests\StoreExamQuestionRequest;

class ExamQuestionController extends Controller
{
    public function create(Exam $exam)
    {
        $this->authorize('manageQuestions', $exam);

        return view('exam-questions.create', compact('exam'));
    }

    public function store(Exam $exam, StoreExamQuestionRequest $request)
    {
        $this->authorize('manageQuestions', $exam);

        $exam->questions()->create($request->validated());

        return redirect()
            ->route('guru.exams.show', $exam)
            ->with('status', 'Soal berhasil ditambahkan.');
    }

    public function destroy(Exam $exam, ExamQuestion $question)
    {
        $this->authorize('manageQuestions', $exam);


        abort_unless($question->exam_id === $exam->id, 404);

        $question->delete();

        return redirect()
            ->route('guru.exams.show', $exam)
            ->with('status', 'Soal berhasil dihapus.');
    }
}