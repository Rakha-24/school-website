<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('access-admin');
    }

    public function rules(): array
    {
        $teacher = $this->route('teacher');
        $teacherId = $teacher?->id;
        $userId = $teacher?->user_id;

        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160', Rule::unique('users', 'email')->ignore($userId)],
            'teacher_number' => ['nullable', 'string', 'max:30', Rule::unique('teachers', 'teacher_number')->ignore($teacherId)],
            'phone' => ['nullable', 'string', 'max:25'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:4096'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }
}
