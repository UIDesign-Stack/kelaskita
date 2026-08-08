<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Violation;
use Illuminate\Http\Request;

class ViolationController extends Controller
{
    public function index(Request $request)
    {
        $classes = SchoolClass::orderBy('name')->get();

        $query = Violation::with(['student.schoolClass', 'recordedBy']);

        if ($request->filled('class_id')) {
            $query->whereHas('student', fn ($q) => $q->where('class_id', $request->class_id));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $violations = $query->latest('date')->get();

        $topStudents = $violations->groupBy('student_id')
            ->map(fn ($group) => [
                'student' => $group->first()->student,
                'total_points' => $group->sum('points'),
                'total_records' => $group->count(),
            ])
            ->sortByDesc('total_points')
            ->take(5);

        return view('violations.index', compact('classes', 'violations', 'topStudents'));
    }
}