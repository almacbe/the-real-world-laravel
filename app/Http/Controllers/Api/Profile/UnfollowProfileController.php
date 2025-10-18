<?php

namespace App\Http\Controllers\Api\Profile;

use App\Domain\Profiles\Actions\UnfollowUserAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UnfollowProfileController extends Controller
{
    public function __construct(
        private readonly UnfollowUserAction $unfollowUser,
    ) {
    }

    public function __invoke(Request $request, string $username): JsonResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(401);
        }

        return ProfileResource::make(
            $this->unfollowUser->execute($user, $username)
        )->response();
    }
}
