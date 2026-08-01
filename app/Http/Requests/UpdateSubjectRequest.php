<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateSubjectRequest extends StoreSubjectRequest
{
    public function rules(): array
    {
        /** @var \App\Models\Subject $subject */
        $subject = $this->route('subject');

        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', Rule::unique('subjects', 'code')->ignore($subject->id)],
        ];
    }
}