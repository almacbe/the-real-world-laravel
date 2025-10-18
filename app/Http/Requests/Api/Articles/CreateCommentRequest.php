<?php

namespace App\Http\Requests\Api\Articles;

use Illuminate\Foundation\Http\FormRequest;

class CreateCommentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'comment' => ['required', 'array'],
            'comment.body' => ['required', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array{body:string}
     */
    public function payload(): array
    {
        /** @var array{body:string} $comment */
        $comment = $this->validated('comment');

        return $comment;
    }
}
