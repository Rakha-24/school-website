<?php

namespace App\Http\Requests\Learning;

use Illuminate\Foundation\Http\FormRequest;

class SubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'answer_text' => ['nullable', 'string', 'max:10000'],
            'file' => ['nullable', 'file', 'max:20480', 'mimes:pdf,doc,docx,txt,jpg,jpeg,png'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (! $this->filled('answer_text') && ! $this->hasFile('file')) {
                $validator->errors()->add('answer_text', 'Isi jawaban atau unggah berkas minimal salah satu.');
            }
        });
    }
}
