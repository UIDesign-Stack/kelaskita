<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreDocumentTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // sesuaikan dengan policy/permission kamu
    }

    /**
     * Normalisasi input sebelum divalidasi.
     * "CP" / "Modul Ajar" -> "cp" / "modul_ajar"
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => Str::slug($this->input('code'), '_'),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'regex:/^[a-z0-9_]+$/', Rule::unique('document_types', 'code')],
            'requires_semester' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Data yang sudah rapi untuk langsung dipakai di create()/update(),
     * termasuk normalisasi boolean dari checkbox HTML.
     */
    public function toModelData(): array
    {
        return [
            'name' => $this->validated('name'),
            'code' => $this->validated('code'),
            'requires_semester' => $this->boolean('requires_semester'),
        ];
    }
}