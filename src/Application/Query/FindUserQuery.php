<?php

namespace App\Application\Query;

use App\Common\Interfaces\Query;

/**
 * @package App\Application\Query
 */
class FindUserQuery implements Query
{
    /** @var int */
    private $userId;

    /**
     * @param int $userId
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

}
