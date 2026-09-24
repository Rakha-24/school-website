<?php

namespace App\Http\Requests\Attendance;

use App\Models\Attendance;
use App\Models\SchoolClass;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        $classId = $this->input('class_id', $this->route('class'));

        $class = $classId ? SchoolClass::findOrFail($classId) : null;
        if (! $class) {
            return false;
        }

        return $this->user()->can('manageClass', $class);
    }

    public function rules(): array
    {
        return [
            'class_id' => ['required', 'exists:classes,id'],
            'date' => ['required', 'date'],
            'records' => ['required', 'array', 'min:1'],
            'records.*.student_id' => ['required', 'exists:students,id'],
            'records.*.status' => ['required', Rule::in(array_keys(Attendance::STATUSES))],
            'records.*.note' => ['nullable', 'string', 'max:255'],
        ];
    }
}
