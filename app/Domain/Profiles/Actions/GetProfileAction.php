<?php

namespace App\Domain\Profiles\Actions;

use App\Domain\Profiles\DataTransferObjects\ProfileData;
use App\Domain\Users\Repositories\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetProfileAction
{
    public function __construct(
        private readonly UserRepositoryInterface $users,
    ) {
    }

    public function execute(string $username, ?User $viewer): ProfileData
    {
        $profileUser = $this->users->findByUsername($username);

        if (! $profileUser) {
            throw new ModelNotFoundException();
        }

        $following = $viewer ? $this->users->isFollowing($viewer, $profileUser) : false;

        return new ProfileData($profileUser, $following);
    }
}
