<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('access-admin');
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160', Rule::unique('users', 'email')->ignore($userId)],
            'role' => ['required', Rule::in(User::ROLES)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'status' => ['nullable', 'in:active,inactive'],
        ];
    }
}
