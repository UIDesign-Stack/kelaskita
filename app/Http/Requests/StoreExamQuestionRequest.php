<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExamQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'in:pilihan_ganda,esai'],
            'question' => ['required', 'string'],
            'option_a' => ['nullable', 'required_if:type,pilihan_ganda', 'string', 'max:255'],
            'option_b' => ['nullable', 'required_if:type,pilihan_ganda', 'string', 'max:255'],
            'option_c' => ['nullable', 'string', 'max:255'],
            'option_d' => ['nullable', 'string', 'max:255'],
            'correct_answer' => ['nullable', 'required_if:type,pilihan_ganda', 'in:A,B,C,D'],
            'weight' => ['required', 'integer', 'min:1', 'max:100'],
        ];
    }
}