<?php

namespace App\Domain\Users\Actions;

use App\Domain\Auth\DataTransferObjects\AuthenticatedUser;
use App\Domain\Auth\Services\TokenService;
use App\Domain\Users\Repositories\UserRepositoryInterface;

class RegisterUserAction
{
    public function __construct(
        private readonly UserRepositoryInterface $users,
        private readonly TokenService $tokenService,
    ) {
    }

    /**
     * @param  array{username:string,email:string,password:string}  $payload
     */
    public function execute(array $payload): AuthenticatedUser
    {
        $user = $this->users->create([
            'username' => $payload['username'],
            'email' => $payload['email'],
            'password' => $payload['password'],
        ]);

        $token = $this->tokenService->createForUser($user);

        return new AuthenticatedUser($user, $token);
    }
}

