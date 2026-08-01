<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreMaterialRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,jpg,png', 'max:10240'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (! $this->filled('subject_id')) {
                return;
            }

            $teacher = Auth::user()->teacher;

            $isOwnSubject = $teacher->teachingAssignments()
                ->where('subject_id', $this->input('subject_id'))
                ->exists();

            if (! $isOwnSubject) {
                $validator->errors()->add('subject_id', 'Mata pelajaran tidak valid untuk akun Anda.');
            }
        });
    }
}