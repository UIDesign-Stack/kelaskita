<?php

namespace App\Http\Requests;

use App\Models\ClassSubjectTeacher;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreExamRequest extends FormRequest
{
    private ?ClassSubjectTeacher $assignment = null;

    public function authorize(): bool
    {
        return (bool) Auth::user()->teacher;
    }

    public function rules(): array
    {
        return [
            'assignment_id' => ['required', 'exists:class_subject_teacher,id'],
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:kuis,tryout,uts,uas'],
            'is_cbt' => ['nullable', 'boolean'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:300'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (! $this->filled('assignment_id')) {
                return;
            }

            $assignment = ClassSubjectTeacher::where('id', $this->input('assignment_id'))
                ->where('teacher_id', Auth::user()->teacher->id)
                ->first();

            if (! $assignment) {
                $validator->errors()->add('assignment_id', 'Penugasan mengajar tidak valid untuk akun Anda.');

                return;
            }

            $this->assignment = $assignment;
        });
    }


    public function toModelData(): array
    {
        return [
            'subject_id' => $this->assignment->subject_id,
            'teacher_id' => Auth::user()->teacher->id,
            'class_id' => $this->assignment->class_id,
            'title' => $this->validated('title'),
            'type' => $this->validated('type'),
            'is_cbt' => $this->boolean('is_cbt', true),
            'duration_minutes' => $this->validated('duration_minutes'),
            'status' => 'pending',
        ];
    }
}