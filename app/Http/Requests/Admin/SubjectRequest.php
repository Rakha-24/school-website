<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('access-admin');
    }

    public function rules(): array
    {
        $subject = $this->route('subject');
        $subjectId = $subject?->id;

        return [
            'name' => ['required', 'string', 'max:120'],
            'code' => ['nullable', 'string', 'max:20', Rule::unique('subjects', 'code')->ignore($subjectId)],
            'description' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
