<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Subject;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $subjects = Subject::orderBy('name')->get();

        $query = Material::with(['subject', 'teacher.user']);

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $materials = $query->latest()->get();

        return view('materials.index', compact('subjects', 'materials'));
    }
}