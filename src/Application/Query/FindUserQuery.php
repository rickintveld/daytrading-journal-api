<?php

namespace App\Application\Query;

use App\Common\Contracts\Query;

class FindUserQuery implements Query
{
    public function __construct(private string $userId)
    {
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

}
