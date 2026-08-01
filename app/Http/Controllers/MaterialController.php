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

        $materials = Material::with(['subject', 'teacher.user'])
            ->when($request->filled('subject_id'), fn ($q) => $q->where('subject_id', $request->subject_id))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('materials.index', compact('subjects', 'materials'));
    }
}