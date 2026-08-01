<?php

namespace App\Http\Requests;

use App\Models\SchoolClass;
use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
 
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'grade_level' => ['required', 'string', 'max:20'],
            'school_year_id' => ['required', 'exists:school_years,id'],
            'homeroom_teacher_id' => ['nullable', 'exists:teachers,id'],
        ];
    }
 
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (empty($this->homeroom_teacher_id)) {
                return;
            }
 
            $exists = SchoolClass::where('homeroom_teacher_id', $this->homeroom_teacher_id)
                ->where('school_year_id', $this->school_year_id)
                ->when($this->route('class'), fn ($q, $class) => $q->where('id', '!=', $class->id))
                ->exists();
 
            if ($exists) {
                $validator->errors()->add(
                    'homeroom_teacher_id',
                    'Guru ini sudah menjadi wali kelas lain di tahun ajaran yang sama.'
                );
            }
        });
    }
}