<?php

namespace App\Http\Requests\Api\Articles;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'article' => ['required', 'array'],
            'article.title' => ['sometimes', 'string', 'max:255'],
            'article.description' => ['sometimes', 'string'],
            'article.body' => ['sometimes', 'string'],
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
        $payload = $this->validated('article');

        return $payload;
    }
}
