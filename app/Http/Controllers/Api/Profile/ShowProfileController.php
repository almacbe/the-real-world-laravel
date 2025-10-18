<?php

namespace App\Http\Controllers\Api\Profile;

use App\Domain\Profiles\Actions\GetProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShowProfileController extends Controller
{
    public function __construct(
        private readonly GetProfileAction $getProfile,
    ) {
    }

    public function __invoke(Request $request, string $username): JsonResponse
    {
        $viewer = $request->user();

        if ($viewer !== null && ! $viewer instanceof User) {
            abort(401);
        }

        return ProfileResource::make(
            $this->getProfile->execute($username, $viewer instanceof User ? $viewer : null)
        )->response();
    }
}
