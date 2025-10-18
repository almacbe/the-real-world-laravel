<?php

namespace App\Domain\Profiles\Actions;

use App\Domain\Profiles\DataTransferObjects\ProfileData;
use App\Domain\Users\Repositories\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class FollowUserAction
{
    public function __construct(
        private readonly UserRepositoryInterface $users,
    ) {
    }

    public function execute(User $follower, string $username): ProfileData
    {
        $profileUser = $this->users->findByUsername($username);

        if (! $profileUser) {
            throw new ModelNotFoundException();
        }

        if ($follower->is($profileUser)) {
            throw ValidationException::withMessages([
                'profile' => [__('You cannot follow yourself.')],
            ]);
        }

        $this->users->follow($follower, $profileUser);

        return new ProfileData($profileUser, true);
    }
}
