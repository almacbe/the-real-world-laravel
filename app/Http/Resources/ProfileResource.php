<?php

namespace App\Http\Resources;

use App\Domain\Profiles\DataTransferObjects\ProfileData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property ProfileData $resource
 */
class ProfileResource extends JsonResource
{
    public function __construct(ProfileData $resource)
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
            'profile' => [
                'username' => $user->username,
                'bio' => $user->bio,
                'image' => $user->image,
                'following' => $this->resource->following,
            ],
        ];
    }
}
