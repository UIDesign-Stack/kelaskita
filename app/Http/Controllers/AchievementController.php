<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function index(Request $request)
    {
        $classes = SchoolClass::orderBy('name')->get();

        $query = Achievement::with(['student.schoolClass', 'recordedBy']);

        if ($request->filled('class_id')) {
            $query->whereHas('student', fn ($q) => $q->where('class_id', $request->class_id));
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        $achievements = $query->latest('date')->get();

        return view('achievements.index', compact('classes', 'achievements'));
    }
}