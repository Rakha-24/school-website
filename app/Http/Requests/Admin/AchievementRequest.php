<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AchievementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('access-admin');
    }

    public function rules(): array
    {
        $achievement = $this->route('achievement');
        $achievementId = $achievement?->id;

        return [
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:220', Rule::unique('achievements', 'slug')->ignore($achievementId)],
            'description' => ['nullable', 'string', 'max:3000'],
            'level' => ['required', Rule::in(['city', 'district', 'province', 'national', 'international'])],
            'year' => ['required', 'integer', 'min:2000', 'max:2100'],
            'category' => ['required', 'string', 'max:80'],
            'participant' => ['nullable', 'string', 'max:200'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:6144'],
            'published_at' => ['nullable', 'date'],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ];
    }
}
