<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuruExamResultController extends Controller
{
    public function index(Exam $exam)
    {
        $this->authorize('manage', $exam);

        $exam->load(['schoolClass.students', 'subject']);

        $results = ExamResult::with('student')
            ->where('exam_id', $exam->id)
            ->get()
            ->keyBy('student_id');

        $rows = $exam->schoolClass->students->map(function ($student) use ($results) {
            return [
                'student' => $student,
                'result' => $results->get($student->id),
            ];
        });

        return view('exam-results.index', compact('exam', 'rows'));
    }

    public function grade(ExamResult $result)
    {
        $exam = $result->exam;
        $this->authorize('manage', $exam);

        abort_unless($result->finished_at, 404, 'Siswa ini belum menyelesaikan ujian.');

        $result->load(['exam.questions', 'answers', 'student']);

        return view('exam-results.grade', compact('result'));
    }

    public function storeGrade(Request $request, ExamResult $result)
    {
        $exam = $result->exam;
        $this->authorize('manage', $exam);

        $result->load('exam.questions', 'answers');

        $validated = $request->validate([
            'scores' => ['required', 'array'],
        ]);

        DB::transaction(function () use ($result, $validated) {
            $totalWeight = 0;
            $earnedWeight = 0;

            foreach ($result->exam->questions as $question) {
                $answer = $result->answers->firstWhere('question_id', $question->id);
                $totalWeight += $question->weight;

                if ($question->type === 'pilihan_ganda') {
                    if ($answer && $answer->is_correct) {
                        $earnedWeight += $question->weight;
                    }
                } else {
                    $manualScore = (int) ($validated['scores'][$question->id] ?? 0);
                    $manualScore = max(0, min($manualScore, $question->weight));

                    $answer?->update(['score' => $manualScore]);
                    $earnedWeight += $manualScore;
                }
            }

            $result->update([
                'score' => $totalWeight > 0 ? round($earnedWeight / $totalWeight * 100, 1) : 0,
            ]);
        });

        return redirect()
            ->route('guru.exams.results.index', $result->exam_id)
            ->with('status', 'Nilai esai berhasil disimpan, skor akhir siswa sudah diperbarui.');
    }
}