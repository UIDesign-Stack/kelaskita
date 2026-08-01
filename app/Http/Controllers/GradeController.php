<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\SchoolClass;
use App\Models\SchoolYear;
use App\Models\Subject;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $classes = SchoolClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $schoolYears = SchoolYear::orderByDesc('name')->get();

        $baseQuery = Grade::query()
            ->when($request->filled('class_id'), function ($q) use ($request) {
                $q->whereHas('student', fn ($sq) => $sq->where('class_id', $request->class_id));
            })
            ->when($request->filled('subject_id'), fn ($q) => $q->where('subject_id', $request->subject_id))
            ->when($request->filled('school_year_id'), fn ($q) => $q->where('school_year_id', $request->school_year_id))
            ->when($request->filled('semester'), fn ($q) => $q->where('semester', $request->semester));

        $averageScore = (clone $baseQuery)->avg('score');
        $averageScore = $averageScore !== null ? round($averageScore, 1) : null;

        $grades = (clone $baseQuery)
            ->with(['student.schoolClass', 'subject', 'teacher.user', 'schoolYear'])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('grades.index', compact('classes', 'subjects', 'schoolYears', 'grades', 'averageScore'));
    }
}