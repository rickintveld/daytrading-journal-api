<?php

namespace App\Application\Command;

use App\Common\Contracts\Command;

class BlockUserCommand implements Command
{
    public function __construct(private string $identifier)
    {
    }

    public function getIdentifier(): string {
        return $this->identifier;
    }
}
