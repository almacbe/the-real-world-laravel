<?php

namespace App\Domain\Tags\Repositories;

use App\Models\Tag;

class TagRepository implements TagRepositoryInterface
{
    public function all(): array
    {
        return Tag::query()
            ->orderBy('name')
            ->pluck('name')
            ->values()
            ->all();
    }
}
