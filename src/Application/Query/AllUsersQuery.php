<?php

namespace App\Application\Query;

use App\Common\Contracts\Query;

class AllUsersQuery implements Query
{
    public function __construct(private bool $isBlocked = false, private bool $isRemoved = false)
    {
    }

    public function isBlocked(): bool
    {
        return $this->isBlocked;
    }

    public function isRemoved(): bool
    {
        return $this->isRemoved;
    }
}
