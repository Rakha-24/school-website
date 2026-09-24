<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FaqRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('access-admin');
    }

    public function rules(): array
    {
        return [
            'question' => ['required', 'string', 'max:300'],
            'answer' => ['required', 'string', 'max:3000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['published', 'hidden'])],
        ];
    }
}
