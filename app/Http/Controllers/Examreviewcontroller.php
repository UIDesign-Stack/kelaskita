<?php

namespace App\Http\Controllers;

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

        $query = Exam::with(['subject', 'schoolClass', 'teacher.user'])->withCount('questions');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $exams = $query->latest()->get();

        $summary = [
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'approved' => Exam::where('status', 'approved')->count(),
            'rejected' => Exam::where('status', 'rejected')->count(),
        ];

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

    public function reject(Exam $exam, Request $request)
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        $exam->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return redirect()
            ->route('ujian.exam-review.index')
            ->with('status', 'Paket ujian "' . $exam->title . '" ditolak, alasan sudah dikirim ke guru.');
    }
}