<?php

namespace App\Http\Controllers\Api\User;

use App\Domain\Auth\DataTransferObjects\AuthenticatedUser;
use App\Domain\Auth\Services\TokenService;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurrentUserController extends Controller
{
    public function __construct(
        private readonly TokenService $tokenService,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(401);
        }

        return UserResource::make(new AuthenticatedUser(
            $user,
            $this->tokenService->createForUser($user),
        ))->response();
    }
}
