<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'status' => ['required', 'array'],
            'status.*' => ['required', 'in:hadir,izin,sakit,alpa'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $submittedIds = array_map('intval', array_keys($this->input('status', [])));

            $validTeacherIds = \App\Models\Teacher::whereIn('id', $submittedIds)->pluck('id')->all();

            $invalidIds = array_diff($submittedIds, $validTeacherIds);

            if (! empty($invalidIds)) {
                $validator->errors()->add('status', 'Terdapat data guru yang tidak valid.');
            }
        });
    }
}