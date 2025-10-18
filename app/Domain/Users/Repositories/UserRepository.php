<?php

namespace App\Domain\Users\Repositories;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $attributes): User
    {
        return User::query()->create($attributes);
    }

    public function findByEmail(string $email): ?User
    {
        return User::query()->where('email', $email)->first();
    }

    public function findByUsername(string $username): ?User
    {
        return User::query()->where('username', $username)->first();
    }
}

