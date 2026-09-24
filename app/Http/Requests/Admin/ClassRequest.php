<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('access-admin');
    }

    public function rules(): array
    {
        $class = $this->route('class');
        $classId = $class?->id;

        return [
            'name' => ['required', 'string', 'max:60'],
            'grade' => ['required', 'integer', 'min:1', 'max:13'],
            'academic_year' => ['required', 'string', 'regex:/^\d{4}\/\d{4}$/'],
            'homeroom_teacher_id' => ['nullable', 'exists:teachers,id'],
            'status' => ['required', Rule::in(['active', 'archived'])],
        ];
    }
}
