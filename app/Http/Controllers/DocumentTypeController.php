<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentTypeRequest;
use App\Http\Requests\UpdateDocumentTypeRequest;
use App\Models\DocumentType;

class DocumentTypeController extends Controller
{
    public function index()
    {
        $documentTypes = DocumentType::withCount('documents')->orderBy('name')->get();

        return view('document-types.index', compact('documentTypes'));
    }

    public function store(StoreDocumentTypeRequest $request)
    {
        DocumentType::create($request->toModelData());

        return back()->with('status', 'Jenis dokumen berhasil ditambahkan.');
    }

    public function update(UpdateDocumentTypeRequest $request, DocumentType $documentType)
    {
        $documentType->update($request->toModelData());

        return back()->with('status', 'Jenis dokumen berhasil diperbarui.');
    }

    public function destroy(DocumentType $documentType)
    {
        if ($documentType->documents()->exists()) {
            return back()->withErrors([
                'delete' => 'Jenis dokumen "' . $documentType->name . '" masih dipakai. Nonaktifkan saja kalau tidak dipakai lagi, jangan dihapus.',
            ]);
        }

        $documentType->delete();

        return back()->with('status', 'Jenis dokumen berhasil dihapus.');
    }
}