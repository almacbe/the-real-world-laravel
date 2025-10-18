<?php

namespace App\Domain\Users\Actions;

use App\Domain\Auth\DataTransferObjects\AuthenticatedUser;
use App\Domain\Auth\Services\TokenService;
use App\Domain\Users\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticateUserAction
{
    public function __construct(
        private readonly UserRepositoryInterface $users,
        private readonly TokenService $tokenService,
    ) {
    }

    /**
     * @param  array{email:string,password:string}  $payload
     */
    public function execute(array $payload): AuthenticatedUser
    {
        $user = $this->users->findByEmail($payload['email']);

        if (! $user || ! Hash::check($payload['password'], $user->password)) {
            throw ValidationException::withMessages([
                'user' => [__('auth.failed')],
            ]);
        }

        $token = $this->tokenService->createForUser($user);

        return new AuthenticatedUser($user, $token);
    }
}
