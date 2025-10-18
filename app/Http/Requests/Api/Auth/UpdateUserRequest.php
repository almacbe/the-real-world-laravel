<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        $userId = $this->user()?->getKey();

        return [
            'user' => ['required', 'array'],
            'user.username' => [
                'sometimes',
                'string',
                'min:1',
                'max:50',
                Rule::unique('users', 'username')->ignore($userId),
            ],
            'user.email' => [
                'sometimes',
                'string',
                'email:rfc',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'user.password' => ['sometimes', 'string', Password::min(8)],
            'user.bio' => ['sometimes', 'nullable', 'string'],
            'user.image' => ['sometimes', 'nullable', 'string', 'max:2048'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        /** @var array<string, mixed> $payload */
        $payload = $this->validated('user');

        $normalized = [];

        foreach ($payload as $key => $value) {
            if ($value === null && ! in_array($key, ['bio', 'image'], true)) {
                continue;
            }

            $normalized[$key] = $value;
        }

        return $normalized;
    }
}
