<?php

namespace App\Domain\Users\Actions;

use App\Domain\Auth\DataTransferObjects\AuthenticatedUser;
use App\Domain\Auth\Services\TokenService;
use App\Domain\Users\Repositories\UserRepositoryInterface;
use App\Models\User;

class UpdateUserAction
{
    public function __construct(
        private readonly UserRepositoryInterface $users,
        private readonly TokenService $tokenService,
    ) {
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function execute(User $user, array $attributes): AuthenticatedUser
    {
        $updated = $this->users->update($user, $attributes);
        $token = $this->tokenService->createForUser($updated);

        return new AuthenticatedUser($updated, $token);
    }
}
