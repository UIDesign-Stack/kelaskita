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

        $query = Grade::with(['student.schoolClass', 'subject', 'teacher.user', 'schoolYear']);

        if ($request->filled('class_id')) {
            $query->whereHas('student', fn ($q) => $q->where('class_id', $request->class_id));
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('school_year_id')) {
            $query->where('school_year_id', $request->school_year_id);
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        $grades = $query->latest()->get();

        $averageScore = $grades->isNotEmpty() ? round($grades->avg('score'), 1) : null;

        return view('grades.index', compact('classes', 'subjects', 'schoolYears', 'grades', 'averageScore'));
    }
}