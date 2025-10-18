<?php

namespace App\Http\Requests\Api\Articles;

use Illuminate\Foundation\Http\FormRequest;

class ListArticlesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tag' => ['sometimes', 'string'],
            'author' => ['sometimes', 'string'],
            'favorited' => ['sometimes', 'string'],
            'limit' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'offset' => ['sometimes', 'integer', 'min:0'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array{tag:?string,author:?string,favorited:?string,limit:int,offset:int}
     */
    public function payload(): array
    {
        $validated = $this->validated();

        return [
            'tag' => $validated['tag'] ?? null,
            'author' => $validated['author'] ?? null,
            'favorited' => $validated['favorited'] ?? null,
            'limit' => isset($validated['limit']) ? (int) $validated['limit'] : 20,
            'offset' => isset($validated['offset']) ? (int) $validated['offset'] : 0,
        ];
    }
}

