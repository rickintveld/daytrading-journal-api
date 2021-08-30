<?php

namespace App\Application\Command;

/**
 * @package App\Application\Command
 */
class UpdateUserCommand extends CreateUserCommand
{
    /** @var int */
    private $identifier;

    /**
     * @return int
     */
    public function getIdentifier(): int
    {
        return $this->identifier;
    }
}
