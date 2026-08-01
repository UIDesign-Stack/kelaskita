<?php

namespace App\Http\Requests;

use App\Models\DocumentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTeacherDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) Auth::user()->teacher;
    }

    protected function failedAuthorization(): void
    {
        abort(403, 'Akun Anda tidak terhubung ke data guru.');
    }

    public function rules(): array
    {
        return [
            'subject_id' => ['required', 'exists:subjects,id'],
            'document_type_id' => ['required', 'exists:document_types,id'],
            'title' => ['required', 'string', 'max:255'],
            'semester' => ['nullable', 'in:ganjil,genap'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $teacher = Auth::user()->teacher;

            if ($this->filled('subject_id')) {
                $isOwnSubject = $teacher->teachingAssignments()
                    ->where('subject_id', $this->input('subject_id'))
                    ->exists();

                if (! $isOwnSubject) {
                    $validator->errors()->add('subject_id', 'Mata pelajaran tidak valid untuk akun Anda.');
                }
            }

            if ($this->filled('document_type_id')) {
                $documentType = DocumentType::find($this->input('document_type_id'));

                if (! $documentType || ! $documentType->is_active) {
                    $validator->errors()->add('document_type_id', 'Jenis dokumen tidak tersedia.');

                    return;
                }

                if ($documentType->requires_semester && ! $this->filled('semester')) {
                    $validator->errors()->add('semester', 'Jenis dokumen ini wajib memilih semester.');
                }
            }
        });
    }
}