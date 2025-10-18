<?php

namespace App\Http\Controllers\Api\Auth;

use App\Domain\Users\Actions\RegisterUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

class RegisterUserController extends Controller
{
    public function __construct(
        private readonly RegisterUserAction $registerUser,
    ) {
    }

    public function __invoke(RegisterUserRequest $request): JsonResponse
    {
        $result = $this->registerUser->execute($request->payload());

        return UserResource::make($result)
            ->response()
            ->setStatusCode(201);
    }
}
