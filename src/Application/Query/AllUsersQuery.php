<?php

namespace App\Application\Query;

use App\Common\Interfaces\Query;

/**
 * @package App\Application\Query
 */
class AllUsersQuery implements Query
{
    /** @var boolean */
    private $isBlocked;

    /** @var boolean */
    private $isRemoved;

    /**
     * AllUsersQuery constructor.
     * @param bool $isBlocked
     * @param bool $isRemoved
     */
    public function __construct(bool $isBlocked = false, bool $isRemoved = false)
    {
        $this->isBlocked = $isBlocked;
        $this->isRemoved = $isRemoved;
    }

    /**
     * @return bool
     */
    public function isBlocked(): bool
    {
        return $this->isBlocked;
    }

    /**
     * @return bool
     */
    public function isRemoved(): bool
    {
        return $this->isRemoved;
    }
}
