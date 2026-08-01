<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'requires_semester' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
    public function toModelData(): array
    {
        return [
            'name' => $this->validated('name'),
            'requires_semester' => $this->boolean('requires_semester'),
            'is_active' => $this->boolean('is_active'),
        ];
    }
}