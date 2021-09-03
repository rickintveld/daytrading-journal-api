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
     * @param int    $identifier
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     * @param int    $capital
     * @param string $password
     */
    public function __construct(int $identifier, string $email, string $firstName, string $lastName, int $capital, string $password)
    {
        parent::__construct($email, $firstName, $lastName, $capital, $password);
        $this->identifier = $identifier;
    }


    /**
     * @return int
     */
    public function getIdentifier(): int
    {
        return $this->identifier;
    }
}
