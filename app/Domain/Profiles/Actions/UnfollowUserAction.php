<?php

namespace App\Domain\Profiles\Actions;

use App\Domain\Profiles\DataTransferObjects\ProfileData;
use App\Domain\Users\Repositories\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UnfollowUserAction
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

        $this->users->unfollow($follower, $profileUser);

        $following = $this->users->isFollowing($follower, $profileUser);

        return new ProfileData($profileUser, $following);
    }
}
