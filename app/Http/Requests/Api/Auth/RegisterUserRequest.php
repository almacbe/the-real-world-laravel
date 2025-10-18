<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user' => ['required', 'array'],
            'user.username' => ['required', 'string', 'min:1', 'max:50', 'unique:users,username'],
            'user.email' => ['required', 'string', 'email:rfc', 'max:255', 'unique:users,email'],
            'user.password' => ['required', 'string', Password::min(8)],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array{username:string,email:string,password:string}
     */
    public function payload(): array
    {
        /** @var array{username:string,email:string,password:string} $user */
        $user = $this->validated('user');

        return $user;
    }
}
