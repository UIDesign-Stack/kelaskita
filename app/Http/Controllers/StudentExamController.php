<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentExamController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        $exams = collect();

        if ($student) {
            $exams = Exam::with(['subject', 'results' => fn ($q) => $q->where('student_id', $student->id)])
                ->where('class_id', $student->class_id)
                ->where('status', 'approved')
                ->where('is_cbt', true)
                ->withCount('questions')
                ->latest()
                ->get();
        }

        return view('student-exams.index', compact('exams', 'student'));
    }

    public function start(Exam $exam)
    {
        $student = $this->authorizeStudent($exam);

        $result = ExamResult::firstOrCreate(
            ['exam_id' => $exam->id, 'student_id' => $student->id],
            ['started_at' => now()]
        );

        if ($result->finished_at) {
            return redirect()->route('siswa.exams.result', $result)
                ->with('status', 'Kamu sudah menyelesaikan ujian ini sebelumnya.');
        }

        return redirect()->route('siswa.exams.take', $result);
    }

    public function take(ExamResult $result)
    {
        $this->authorizeResult($result);

        if ($result->finished_at) {
            return redirect()->route('siswa.exams.result', $result);
        }

        $result->load(['exam.questions']);

        $deadline = $result->started_at->copy()->addMinutes($result->exam->duration_minutes);

        $existingAnswers = ExamAnswer::where('exam_result_id', $result->id)->pluck('answer', 'question_id');

        return view('student-exams.take', compact('result', 'deadline', 'existingAnswers'));
    }

    public function submit(Request $request, ExamResult $result)
    {
        $this->authorizeResult($result);

        if ($result->finished_at) {
            return redirect()->route('siswa.exams.result', $result);
        }

        $result->load('exam.questions');

        $validated = $request->validate([
            'answers' => ['nullable', 'array'],
            // Sebelumnya cuma memvalidasi 'answers' sebagai array tanpa mengecek isinya.
            // Kalau ada yang kirim answers[id][]=A&answers[id][]=B (array di dalam array),
            // strtoupper() di bawah bakal fatal error karena menerima array, bukan string.
            'answers.*' => ['nullable', 'string', 'max:5000'],
        ]);

        $answers = $validated['answers'] ?? [];

        // TODO (perlu konfirmasi): batas waktu ujian ($deadline di method take()) tidak
        // divalidasi ulang di sini. Submit yang datang jauh melewati durasi ujian tetap
        // diterima sebagai submit tepat waktu. Kalau kebijakannya harus menolak/menandai
        // submit telat, tambahkan pengecekan now() vs started_at+duration_minutes di sini.

        DB::transaction(function () use ($result, $answers) {
            $totalWeight = 0;
            $earnedWeight = 0;
            $hasEssay = false;

            foreach ($result->exam->questions as $question) {
                $studentAnswer = $answers[$question->id] ?? null;
                $isCorrect = null;

                if ($question->type === 'pilihan_ganda') {
                    $isCorrect = $studentAnswer !== null && strtoupper($studentAnswer) === $question->correct_answer;
                    $totalWeight += $question->weight;
                    if ($isCorrect) {
                        $earnedWeight += $question->weight;
                    }
                } else {
                    $hasEssay = true;
                }

                ExamAnswer::updateOrCreate(
                    ['exam_result_id' => $result->id, 'question_id' => $question->id],
                    [
                        'answer' => $studentAnswer,
                        'is_correct' => $isCorrect,
                        // TODO (perlu konfirmasi): baris ini sebelumnya
                        // `$question->type === 'pilihan_ganda' ? null : null` — kedua cabang
                        // ternary-nya sama-sama null, jadi kolom 'score' per-soal ini tidak
                        // pernah benar-benar terisi apa pun kondisinya. Kalau memang 'score'
                        // per-jawaban ini dipakai di tempat lain (misal rekap guru menilai
                        // esai, atau laporan per-soal), perlu diisi dengan nilai yang benar
                        // (mis. $isCorrect ? $question->weight : 0 untuk pilihan ganda, dan
                        // null untuk esai yang menunggu penilaian manual guru).
                        'score' => null,
                    ]
                );
            }

            $result->update([
                'finished_at' => now(),
                'score' => (!$hasEssay && $totalWeight > 0) ? round($earnedWeight / $totalWeight * 100, 1) : null,
            ]);
        });

        return redirect()
            ->route('siswa.exams.result', $result)
            ->with('status', 'Jawaban berhasil dikumpulkan.');
    }

    public function result(ExamResult $result)
    {
        $this->authorizeResult($result);

        if (!$result->finished_at) {
            return redirect()->route('siswa.exams.take', $result);
        }

        $result->load(['exam.subject', 'exam.questions', 'answers']);

        return view('student-exams.result', compact('result'));
    }

    private function authorizeStudent(Exam $exam)
    {
        $student = Auth::user()->student;

        abort_if(!$student, 403, 'Akun Anda tidak terhubung ke data siswa.');
        abort_if($exam->status !== 'approved', 403, 'Ujian ini belum disetujui admin.');
        abort_if($exam->class_id !== $student->class_id, 403, 'Ujian ini bukan untuk kelas Anda.');

        return $student;
    }

    private function authorizeResult(ExamResult $result): void
    {
        $student = Auth::user()->student;

        abort_if(!$student || $result->student_id !== $student->id, 403, 'Anda tidak memiliki akses ke hasil ujian ini.');
    }
}