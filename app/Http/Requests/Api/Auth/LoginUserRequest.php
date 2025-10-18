<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user' => ['required', 'array'],
            'user.email' => ['required', 'string', 'email:rfc'],
            'user.password' => ['required', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array{email:string,password:string}
     */
    public function payload(): array
    {
        /** @var array{email:string,password:string} $user */
        $user = $this->validated('user');

        return $user;
    }
}
