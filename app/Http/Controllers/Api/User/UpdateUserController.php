<?php

namespace App\Http\Controllers\Api\User;

use App\Domain\Users\Actions\UpdateUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UpdateUserController extends Controller
{
    public function __construct(
        private readonly UpdateUserAction $updateUser,
    ) {
    }

    public function __invoke(UpdateUserRequest $request): JsonResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(401);
        }

        $result = $this->updateUser->execute($user, $request->payload());

        return UserResource::make($result)->response();
    }
}
