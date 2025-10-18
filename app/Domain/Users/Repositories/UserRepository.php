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

    public function update(User $user, array $attributes): User
    {
        $user->fill($attributes);
        $user->save();

        return $user->refresh();
    }

    public function findByUsernameOrFail(string $username): User
    {
        return User::query()->where('username', $username)->firstOrFail();
    }

    public function follow(User $follower, User $followed): void
    {
        $follower->following()->syncWithoutDetaching([$followed->getKey()]);
    }

    public function unfollow(User $follower, User $followed): void
    {
        $follower->following()->detach($followed->getKey());
    }

    public function isFollowing(User $follower, User $followed): bool
    {
        return $follower->following()->whereKey($followed->getKey())->exists();
    }
}
