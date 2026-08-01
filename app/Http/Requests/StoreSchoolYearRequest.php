<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolYearRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // sesuaikan dengan policy/permission kamu
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:20'],
            'semester' => ['required', 'in:ganjil,genap'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function toModelData(): array
    {
        return [
            'name' => $this->validated('name'),
            'semester' => $this->validated('semester'),
            'is_active' => $this->boolean('is_active'),
        ];
    }
}