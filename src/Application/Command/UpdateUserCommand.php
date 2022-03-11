<?php

namespace App\Application\Command;

class UpdateUserCommand extends CreateUserCommand
{
    public function __construct(
        private string $identifier,
        string $email,
        string $firstName,
        string $lastName,
        int $capital,
        string $password
    ) {
        parent::__construct($email, $firstName, $lastName, $capital, $password);
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}
