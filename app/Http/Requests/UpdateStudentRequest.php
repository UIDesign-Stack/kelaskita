<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // sesuaikan dengan policy/permission kamu
    }

    public function rules(): array
    {
        /** @var \App\Models\Student $student */
        $student = $this->route('student');

        return [
            'name' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'nis' => ['required', 'string', 'max:50', Rule::unique('students', 'nis')->ignore($student->id)],
            'nisn' => ['nullable', 'string', 'max:50', Rule::unique('students', 'nisn')->ignore($student->id)],
            'class_id' => ['required', 'exists:classes,id'],
            'gender' => ['required', 'in:L,P'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'status' => ['required', 'in:aktif,pindah,lulus,keluar'],
            'create_account' => ['nullable', 'boolean'],
            'email' => [
                'nullable',
                'required_if:create_account,1',
                'email',
                Rule::unique('users', 'email')->ignore($student->user_id),
            ],
            'password' => ['nullable', 'min:8'],
        ];
    }
}