<?php

namespace App\Application\Command;

use App\Common\Contracts\Command;

/**
 * @package App\Application\Command
 */
class BlockUserCommand implements Command
{
    private string $identifier;

    /**
     * @param string $identifier
     */
    public function __construct(string $identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string {
        return $this->identifier;
    }
}
