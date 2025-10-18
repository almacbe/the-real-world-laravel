<?php

namespace App\Domain\Users\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create(array $attributes): User;

    public function findByEmail(string $email): ?User;

    public function findByUsername(string $username): ?User;
}

