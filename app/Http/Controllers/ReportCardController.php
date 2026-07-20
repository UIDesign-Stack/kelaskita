<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\ReportCard;
use App\Models\ReportCardDetail;
use App\Models\SchoolClass;
use App\Models\SchoolYear;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportCardController extends Controller
{
    public function index(Request $request)
    {
        $classes = SchoolClass::orderBy('name')->get();
        $schoolYears = SchoolYear::orderByDesc('name')->get();

        $students = collect();

        if ($request->filled('class_id') && $request->filled('semester') && $request->filled('school_year_id')) {
            $students = Student::where('class_id', $request->class_id)
                ->orderBy('name')
                ->get()
                ->map(function ($student) use ($request) {
                    $student->reportCard = ReportCard::where('student_id', $student->id)
                        ->where('semester', $request->semester)
                        ->where('school_year_id', $request->school_year_id)
                        ->first();

                    return $student;
                });
        }

        return view('report-cards.index', compact('classes', 'schoolYears', 'students'));
    }

    public function show(Student $student, Request $request)
    {
        $request->validate([
            'semester' => ['required', 'in:ganjil,genap'],
            'school_year_id' => ['required', 'exists:school_years,id'],
        ]);

        $reportCard = ReportCard::firstOrCreate(
            [
                'student_id' => $student->id,
                'semester' => $request->semester,
                'school_year_id' => $request->school_year_id,
            ],
            [
                'class_id' => $student->class_id,
                'status' => 'draft',
            ]
        );

        // Generate ulang nilai draft dari tabel grades (hanya jika masih draft)
        if ($reportCard->status === 'draft') {
            $averages = Grade::where('student_id', $student->id)
                ->where('semester', $request->semester)
                ->where('school_year_id', $request->school_year_id)
                ->select('subject_id', DB::raw('AVG(score) as avg_score'))
                ->groupBy('subject_id')
                ->get();

            foreach ($averages as $row) {
                ReportCardDetail::updateOrCreate(
                    ['report_card_id' => $reportCard->id, 'subject_id' => $row->subject_id],
                    [
                        'final_score' => round($row->avg_score, 1),
                        'predicate' => $this->predicate($row->avg_score),
                    ]
                );
            }
        }

        $reportCard->load(['details.subject', 'student', 'schoolYear', 'schoolClass']);
        $overallAverage = $reportCard->details->isNotEmpty() ? round($reportCard->details->avg('final_score'), 1) : null;

        return view('report-cards.show', compact('reportCard', 'overallAverage'));
    }

    public function finalize(ReportCard $reportCard)
    {
        $reportCard->update([
            'status' => 'final',
            'finalized_by' => Auth::id(),
            'finalized_at' => now(),
        ]);

        return redirect()
            ->route('akademik.report-cards.show', [
                'student' => $reportCard->student_id,
                'semester' => $reportCard->semester,
                'school_year_id' => $reportCard->school_year_id,
            ])
            ->with('status', 'Rapor berhasil difinalisasi dan terkunci.');
    }

    private function predicate(float $score): string
    {
        return match (true) {
            $score >= 90 => 'A',
            $score >= 80 => 'B',
            $score >= 70 => 'C',
            default => 'D',
        };
    }
}