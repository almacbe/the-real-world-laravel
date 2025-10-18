<?php

namespace App\Http\Controllers\Api\Profile;

use App\Domain\Profiles\Actions\FollowUserAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FollowProfileController extends Controller
{
    public function __construct(
        private readonly FollowUserAction $followUser,
    ) {
    }

    public function __invoke(Request $request, string $username): JsonResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(401);
        }

        return ProfileResource::make(
            $this->followUser->execute($user, $username)
        )->response();
    }
}
