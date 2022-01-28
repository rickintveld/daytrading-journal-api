<?php

namespace App\Application\Command;

/**
 * @package App\Application\Command
 */
class UpdateUserCommand extends CreateUserCommand
{
    private string $identifier;

    /**
     * @param string $identifier
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     * @param int    $capital
     * @param string $password
     */
    public function __construct(string $identifier, string $email, string $firstName, string $lastName, int $capital, string $password)
    {
        parent::__construct($email, $firstName, $lastName, $capital, $password);
        $this->identifier = $identifier;
    }


    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}
