<?php

namespace App\Http\Controllers\Api\Auth;

use App\Domain\Users\Actions\AuthenticateUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

class LoginUserController extends Controller
{
    public function __construct(
        private readonly AuthenticateUserAction $authenticateUser,
    ) {
    }

    public function __invoke(LoginUserRequest $request): JsonResponse
    {
        $result = $this->authenticateUser->execute($request->payload());

        return UserResource::make($result)->response();
    }
}
