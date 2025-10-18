<?php

namespace App\Http\Resources;

use App\Domain\Auth\DataTransferObjects\AuthenticatedUser;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property AuthenticatedUser $resource
 */
class UserResource extends JsonResource
{
    public function __construct(AuthenticatedUser $resource)
    {
        parent::__construct($resource);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $this->resource->user;

        return [
            'user' => [
                'email' => $user->email,
                'username' => $user->username,
                'bio' => $user->bio,
                'image' => $user->image,
                'token' => $this->resource->token,
            ],
        ];
    }
}

