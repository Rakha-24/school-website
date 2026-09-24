<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('access-admin');
    }

    public function rules(): array
    {
        $news = $this->route('news');
        $newsId = $news?->id;

        return [
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:220', Rule::unique('news', 'slug')->ignore($newsId)],
            'excerpt' => ['nullable', 'string', 'max:400'],
            'content' => ['required', 'string'],
            'category' => ['required', 'string', 'max:80'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:6144'],
            'published_at' => ['nullable', 'date'],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ];
    }
}
