<?php

namespace App\Http\Controllers;

use App\Models\Exam;

class ExamAnalysisController extends Controller
{
    public function show(Exam $exam)
    {
        $this->authorize('manage', $exam);

        $exam->load('questions.examAnswers');

        $totalFinished = $exam->results()->whereNotNull('finished_at')->count();

        $analysis = $exam->questions->map(function ($question) {
            $answers = $question->examAnswers;

            if ($question->type === 'pilihan_ganda') {
                $totalAnswered = $answers->whereNotNull('answer')->count();
                $correctCount = $answers->where('is_correct', true)->count();
                $percentage = $totalAnswered > 0 ? round($correctCount / $totalAnswered * 100, 1) : null;

                $difficulty = match (true) {
                    $percentage === null => null,
                    $percentage < 40 => 'Sulit',
                    $percentage < 70 => 'Sedang',
                    default => 'Mudah',
                };

                $optionDistribution = $answers->whereNotNull('answer')
                    ->groupBy(fn ($a) => strtoupper($a->answer))
                    ->map->count();
            } else {
                $totalAnswered = $answers->whereNotNull('answer')->count();
                $correctCount = null;
                $percentage = null;
                $difficulty = null;
                $optionDistribution = collect();
            }

            return [
                'question' => $question,
                'total_answered' => $totalAnswered,
                'correct_count' => $correctCount,
                'percentage' => $percentage,
                'difficulty' => $difficulty,
                'option_distribution' => $optionDistribution,
            ];
        });

        return view('exam-analysis.show', compact('exam', 'analysis', 'totalFinished'));
    }
}