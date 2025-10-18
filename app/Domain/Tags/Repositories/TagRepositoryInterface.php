<?php

namespace App\Domain\Tags\Repositories;

interface TagRepositoryInterface
{
    /**
     * @return string[]
     */
    public function all(): array;
}
