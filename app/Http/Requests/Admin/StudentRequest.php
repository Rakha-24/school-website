<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('access-admin');
    }

    public function rules(): array
    {
        $student = $this->route('student');
        $studentId = $student?->id;
        $userId = $student?->user_id;

        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160', Rule::unique('users', 'email')->ignore($userId)],
            'student_number' => ['required', 'string', 'max:30', Rule::unique('students', 'student_number')->ignore($studentId)],
            'national_student_number' => ['nullable', 'string', 'max:20', Rule::unique('students', 'national_student_number')->ignore($studentId)],
            'class_id' => ['required', 'exists:classes,id'],
            'enrollment_year' => ['required', 'integer', 'min:1990', 'max:2100'],
            'phone' => ['nullable', 'string', 'max:25'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:4096'],
            'status' => ['required', Rule::in(['active', 'inactive', 'graduate', 'dropped'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }
}
