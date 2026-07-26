<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use App\Models\TeachingDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherDocumentController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::user()->teacher;

        $query = $teacher
            ? TeachingDocument::with(['subject', 'documentType'])->where('teacher_id', $teacher->id)
            : TeachingDocument::whereRaw('1 = 0');

        if ($request->filled('type')) {
            $query->whereHas('documentType', fn ($q) => $q->where('code', $request->type));
        }

        $documents = $query->latest()->get();
        $documentTypes = DocumentType::where('is_active', true)->orderBy('name')->get();

        return view('documents-input.index', compact('documents', 'documentTypes'));
    }

    public function create(Request $request)
    {
        $teacher = Auth::user()->teacher;

        $subjects = $teacher
            ? $teacher->teachingAssignments()->with('subject')->get()->pluck('subject')->unique('id')
            : collect();

        $documentTypes = DocumentType::where('is_active', true)->orderBy('name')->get();

        $selectedType = $request->query('type')
            ? $documentTypes->firstWhere('code', $request->query('type'))
            : $documentTypes->first();

        return view('documents-input.create', compact('subjects', 'documentTypes', 'selectedType'));
    }

    public function store(Request $request)
    {
        $teacher = Auth::user()->teacher;

        abort_if(!$teacher, 403, 'Akun Anda tidak terhubung ke data guru.');

        $validated = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'document_type_id' => ['required', 'exists:document_types,id'],
            'title' => ['required', 'string', 'max:255'],
            'semester' => ['nullable', 'in:ganjil,genap'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ]);

        $documentType = DocumentType::findOrFail($validated['document_type_id']);

        if ($documentType->requires_semester && empty($validated['semester'])) {
            return back()->withInput()->withErrors(['semester' => 'Jenis dokumen ini wajib memilih semester.']);
        }

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('documents', 'public');
        }

        TeachingDocument::create([
            'subject_id' => $validated['subject_id'],
            'teacher_id' => $teacher->id,
            'document_type_id' => $documentType->id,
            'title' => $validated['title'],
            'semester' => $documentType->requires_semester ? $validated['semester'] : null,
            'file_path' => $filePath,
        ]);

        return redirect()
            ->route('guru.documents.index')
            ->with('status', $documentType->name . ' berhasil di-upload.');
    }
}