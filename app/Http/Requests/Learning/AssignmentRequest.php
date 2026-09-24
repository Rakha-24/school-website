<?php

namespace App\Http\Requests\Learning;

use Illuminate\Foundation\Http\FormRequest;

class AssignmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create-assignments');
    }

    public function rules(): array
    {
        return [
            'class_subject_id' => ['required', 'exists:class_subjects,id'],
            'title' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'max:20480', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip'],
            'due_at' => ['required', 'date', 'after_or_equal:now'],
        ];
    }
}
