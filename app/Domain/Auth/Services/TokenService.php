<?php

namespace App\Domain\Auth\Services;

use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;

class TokenService
{
    public function __construct(
        private readonly JWTAuth $jwtAuth,
    ) {
    }

    public function createForUser(User $user): string
    {
        return $this->jwtAuth->fromUser($user);
    }
}

