<?php

namespace App\Http\Controllers;

use App\Http\Requests\RejectExamRequest;
use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamReviewController extends Controller
{
    public function index(Request $request)
    {
        $classes = SchoolClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        $baseQuery = Exam::query()
            ->when($request->filled('class_id'), fn ($q) => $q->where('class_id', $request->class_id))
            ->when($request->filled('subject_id'), fn ($q) => $q->where('subject_id', $request->subject_id));

        $summary = (clone $baseQuery)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $summary = [
            'pending' => $summary['pending'] ?? 0,
            'approved' => $summary['approved'] ?? 0,
            'rejected' => $summary['rejected'] ?? 0,
        ];

        $exams = (clone $baseQuery)
            ->with(['subject', 'schoolClass', 'teacher.user'])
            ->withCount('questions')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('exam-review.index', compact('classes', 'subjects', 'exams', 'summary'));
    }

    public function show(Exam $exam)
    {
        $exam->load(['questions', 'subject', 'schoolClass', 'teacher.user', 'reviewedBy']);

        return view('exam-review.show', compact('exam'));
    }

    public function approve(Exam $exam)
    {
        $exam->update([
            'status' => 'approved',
            'rejection_reason' => null,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()
            ->route('ujian.exam-review.index')
            ->with('status', 'Paket ujian "' . $exam->title . '" berhasil disetujui.');
    }

    public function reject(Exam $exam, RejectExamRequest $request)
    {
        $exam->update([
            'status' => 'rejected',
            'rejection_reason' => $request->validated('rejection_reason'),
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()
            ->route('ujian.exam-review.index')
            ->with('status', 'Paket ujian "' . $exam->title . '" ditolak, alasan sudah dikirim ke guru.');
    }
}