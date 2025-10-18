<?php

namespace App\Http\Requests\Api\Articles;

use Illuminate\Foundation\Http\FormRequest;

class CreateArticleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'article' => ['required', 'array'],
            'article.title' => ['required', 'string', 'max:255'],
            'article.description' => ['required', 'string'],
            'article.body' => ['required', 'string'],
            'article.tagList' => ['sometimes', 'array'],
            'article.tagList.*' => ['required', 'string', 'max:50'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array{title:string,description:string,body:string,tagList?:array<int, string>}
     */
    public function payload(): array
    {
        /** @var array{title:string,description:string,body:string,tagList?:array<int, string>} $article */
        $article = $this->validated('article');

        return $article;
    }
}
