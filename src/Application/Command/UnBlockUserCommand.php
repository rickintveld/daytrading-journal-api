<?php

namespace App\Application\Command;

use App\Common\Interfaces\Command;

/**
 * @package App\Application\Command
 */
class UnBlockUserCommand implements Command
{
    private int $identifier;

    /**
     * @param int $identifier
     */
    public function __construct(int $identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return int
     */
    public function getIdentifier(): int {
        return $this->identifier;
    }
}
