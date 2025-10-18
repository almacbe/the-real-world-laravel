<?php

namespace App\Domain\Users\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create(array $attributes): User;

    public function findByEmail(string $email): ?User;

    public function findByUsername(string $username): ?User;

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(User $user, array $attributes): User;

    public function findByUsernameOrFail(string $username): User;

    public function follow(User $follower, User $followed): void;

    public function unfollow(User $follower, User $followed): void;

    public function isFollowing(User $follower, User $followed): bool;
}
