<?php

namespace App\Http\Requests\Api\Articles;

use Illuminate\Foundation\Http\FormRequest;

class FeedArticlesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'limit' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'offset' => ['sometimes', 'integer', 'min:0'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array{limit:int,offset:int}
     */
    public function payload(): array
    {
        $validated = $this->validated();

        return [
            'limit' => isset($validated['limit']) ? (int) $validated['limit'] : 20,
            'offset' => isset($validated['offset']) ? (int) $validated['offset'] : 0,
        ];
    }
}
