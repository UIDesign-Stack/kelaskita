<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherMaterialController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;

        $materials = $teacher
            ? Material::with('subject')->where('teacher_id', $teacher->id)->latest()->get()
            : collect();

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

    public function store(Request $request)
    {
        $teacher = Auth::user()->teacher;

        abort_if(!$teacher, 403, 'Akun Anda tidak terhubung ke data guru.');

        $validated = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,jpg,png', 'max:10240'],
        ]);

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