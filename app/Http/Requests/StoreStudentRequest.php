<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // sesuaikan dengan policy/permission kamu
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'nis' => ['required', 'string', 'max:50', Rule::unique('students', 'nis')],
            'nisn' => ['nullable', 'string', 'max:50', Rule::unique('students', 'nisn')],
            'class_id' => ['required', 'exists:classes,id'],
            'gender' => ['required', 'in:L,P'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'status' => ['required', 'in:aktif,pindah,lulus,keluar'],
            'create_account' => ['nullable', 'boolean'],
            'email' => ['nullable', 'required_if:create_account,1', 'email', Rule::unique('users', 'email')],
            'password' => ['nullable', 'required_if:create_account,1', 'min:8'],
        ];
    }
}