<?php

namespace App\Http\Requests;

use App\Models\SchoolClass;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreWalasAttendanceRequest extends FormRequest
{
    private ?SchoolClass $homeroomClass = null;
    private bool $resolved = false;

    public function authorize(): bool
    {
        return (bool) $this->resolveHomeroomClass();
    }

    protected function failedAuthorization(): void
    {
        abort(403, 'Anda bukan wali kelas manapun.');
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
            $class = $this->resolveHomeroomClass();

            $validStudentIds = $class->students()->pluck('id')->all();

            $submittedIds = array_map('intval', array_keys($this->input('status', [])));
            $invalidIds = array_diff($submittedIds, $validStudentIds);

            if (! empty($invalidIds)) {
                $validator->errors()->add('status', 'Terdapat siswa yang tidak terdaftar di kelas ini.');
            }
        });
    }

    public function resolveHomeroomClass(): ?SchoolClass
    {
        if (! $this->resolved) {
            $teacher = Auth::user()->teacher;
            $this->homeroomClass = $teacher?->activeHomeroomClass();
            $this->resolved = true;
        }

        return $this->homeroomClass;
    }
}