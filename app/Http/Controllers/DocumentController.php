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

        $query = TeachingDocument::with(['subject', 'teacher.user', 'documentType']);

        if ($request->filled('document_type_id')) {
            $query->where('document_type_id', $request->document_type_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $documents = $query->latest()->get();

        return view('documents.index', compact('subjects', 'documentTypes', 'documents'));
    }
}