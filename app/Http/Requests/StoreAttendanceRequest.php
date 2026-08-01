<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        $assignment = $this->route('assignment');

        return $assignment && Gate::allows('manage', $assignment);
    }

    protected function failedAuthorization(): void
    {
        $assignment = $this->route('assignment');
        $reason = Gate::inspect('manage', $assignment)->message();

        abort(403, $reason ?: 'Anda tidak memiliki akses ke kelas/mapel ini.');
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
            $assignment = $this->route('assignment');

            $validStudentIds = $assignment->schoolClass->students()->pluck('id')->all();

            $submittedIds = array_keys($this->input('status', []));
            $invalidIds = array_diff(array_map('intval', $submittedIds), $validStudentIds);

            if (! empty($invalidIds)) {
                $validator->errors()->add('status', 'Terdapat siswa yang tidak terdaftar di kelas ini.');
            }
        });
    }
}