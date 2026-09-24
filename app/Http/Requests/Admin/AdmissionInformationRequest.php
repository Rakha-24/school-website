<?php

namespace App\Http\Requests\Admin;

use App\Models\AdmissionInformation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdmissionInformationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('access-admin');
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(array_keys(AdmissionInformation::TYPES))],
            'title' => ['required', 'string', 'max:200'],
            'body' => ['required', 'string', 'max:6000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['published', 'hidden'])],
        ];
    }
}
