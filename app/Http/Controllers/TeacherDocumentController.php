<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeacherDocumentRequest;
use App\Models\DocumentType;
use App\Models\TeachingDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherDocumentController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::user()->teacher;

        $documents = $teacher
            ? TeachingDocument::with(['subject', 'documentType'])
                ->where('teacher_id', $teacher->id)
                ->when($request->filled('type'), function ($q) use ($request) {
                    $q->whereHas('documentType', fn ($sq) => $sq->where('code', $request->type));
                })
                ->latest()
                ->paginate(15)
                ->withQueryString()
            : TeachingDocument::whereRaw('1 = 0')->paginate(15);

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

    public function store(StoreTeacherDocumentRequest $request)
    {
        $teacher = Auth::user()->teacher;
        $validated = $request->validated();
        $documentType = DocumentType::findOrFail($validated['document_type_id']);

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