<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExtracurricularRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('access-admin');
    }

    public function rules(): array
    {
        $extra = $this->route('extracurricular');
        $extraId = $extra?->id;

        return [
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:140', Rule::unique('extracurriculars', 'slug')->ignore($extraId)],
            'description' => ['required', 'string', 'max:4000'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:6144'],
            'schedule' => ['nullable', 'string', 'max:160'],
            'leader' => ['nullable', 'string', 'max:120'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ];
    }
}
