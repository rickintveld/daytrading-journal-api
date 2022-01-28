<?php

namespace App\Application\Query;

use App\Common\Contracts\Query;

/**
 * @package App\Application\Query
 */
class FindUserQuery implements Query
{
    private string $userId;

    /**
     * @param string $userId
     */
    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

}
