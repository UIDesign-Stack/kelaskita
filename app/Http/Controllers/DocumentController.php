<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use App\Models\Subject;
use App\Models\TeachingDocument;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $subjects = Subject::orderBy('name')->get();
        $documentTypes = DocumentType::orderBy('name')->get();

        $documents = TeachingDocument::with(['subject', 'teacher.user', 'documentType'])
            ->when($request->filled('document_type_id'), function ($query) use ($request) {
                $query->where('document_type_id', $request->document_type_id);
            })
            ->when($request->filled('subject_id'), function ($query) use ($request) {
                $query->where('subject_id', $request->subject_id);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('documents.index', compact('subjects', 'documentTypes', 'documents'));
    }
}