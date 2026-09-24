<?php

namespace App\Http\Requests\Admin;

use App\Models\ClassSubject;
use Illuminate\Foundation\Http\FormRequest;

class ClassSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('access-admin');
    }

    public function rules(): array
    {
        $id = $this->route('classSubject')?->id;

        return [
            'class_id' => ['required', 'exists:classes,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'hours_per_week' => ['nullable', 'integer', 'min:1', 'max:40'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $data = $this->all();
            if (empty($data['class_id']) || empty($data['subject_id'])) {
                return;
            }

            $exists = app(ClassSubject::class)
                ->where('class_id', $data['class_id'])
                ->where('subject_id', $data['subject_id']);

            if ($id = $this->route('classSubject')?->id) {
                $exists->where('id', '!=', $id);
            }

            if ($exists->exists()) {
                $validator->errors()->add('subject_id', 'Mata pelajaran ini sudah terdaftar untuk kelas tersebut.');
            }
        });
    }
}
