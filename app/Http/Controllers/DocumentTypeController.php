<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DocumentTypeController extends Controller
{
    public function index()
    {
        $documentTypes = DocumentType::withCount('documents')->orderBy('name')->get();

        return view('document-types.index', compact('documentTypes'));
    }

    public function store(Request $request)
    {
        // Normalisasi otomatis: "CP" / "Modul Ajar" -> "cp" / "modul_ajar"
        $request->merge([
            'code' => Str::slug($request->input('code'), '_'),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'regex:/^[a-z0-9_]+$/', Rule::unique('document_types', 'code')],
            'requires_semester' => ['nullable', 'boolean'],
        ]);

        DocumentType::create([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'requires_semester' => $request->boolean('requires_semester'),
        ]);

        return back()->with('status', 'Jenis dokumen berhasil ditambahkan.');
    }

    public function update(Request $request, DocumentType $documentType)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'requires_semester' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $documentType->update([
            'name' => $validated['name'],
            'requires_semester' => $request->boolean('requires_semester'),
            'is_active' => $request->boolean('is_active'),
        ]);

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