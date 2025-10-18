<?php

namespace App\Domain\Profiles\DataTransferObjects;

use App\Models\User;

class ProfileData
{
    public function __construct(
        public readonly User $user,
        public readonly bool $following,
    ) {
    }
}
