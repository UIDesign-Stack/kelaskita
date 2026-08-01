<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaterialRequest;
use App\Models\Material;
use Illuminate\Support\Facades\Auth;

class TeacherMaterialController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;

        $materials = $teacher
            ? Material::with('subject')->where('teacher_id', $teacher->id)->latest()->paginate(15)->withQueryString()
            : Material::whereRaw('1 = 0')->paginate(15);

        return view('materials-input.index', compact('materials', 'teacher'));
    }

    public function create()
    {
        $teacher = Auth::user()->teacher;

        $subjects = $teacher
            ? $teacher->teachingAssignments()->with('subject')->get()->pluck('subject')->unique('id')
            : collect();

        return view('materials-input.create', compact('subjects'));
    }

    public function store(StoreMaterialRequest $request)
    {
        $teacher = Auth::user()->teacher;
        $validated = $request->validated();

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('materials', 'public');
        }

        Material::create([
            'subject_id' => $validated['subject_id'],
            'teacher_id' => $teacher->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'file_path' => $filePath,
        ]);

        return redirect()
            ->route('guru.materials.index')
            ->with('status', 'Materi ajar berhasil di-upload.');
    }
}