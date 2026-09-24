<?php

namespace App\Http\Requests\Learning;

use Illuminate\Foundation\Http\FormRequest;

class MaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create-materials');
    }

    public function rules(): array
    {
        return [
            'class_subject_id' => ['required', 'exists:class_subjects,id'],
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:2000'],
            'content' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'max:20480', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip'],
            'status' => ['required', 'in:draft,published'],
        ];
    }
}
