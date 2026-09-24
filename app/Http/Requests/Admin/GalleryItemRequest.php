<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GalleryItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('access-admin');
    }

    public function rules(): array
    {
        $item = $this->route('galleryItem');
        $itemId = $item?->id;

        return [
            'title' => ['required', 'string', 'max:200'],
            'category' => ['required', 'string', 'max:80'],
            'image' => [$item ? 'nullable' : 'required', 'image', 'mimes:jpeg,png,webp', 'max:8192'],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ];
    }
}
