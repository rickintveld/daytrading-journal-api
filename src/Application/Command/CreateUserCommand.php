<?php

namespace App\Application\Command;

use App\Common\Interfaces\Command;

/**
 * @package App\Application\Command
 */
class CreateUserCommand implements Command
{
    private string $email;

    private string $firstName;

    private string $lastName;

    private int $capital;

    private string $password;

    /**
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     * @param int    $capital
     * @param string $password
     */
    public function __construct(string $email, string $firstName, string $lastName, int $capital, string $password)
    {
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->capital = $capital;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return int
     */
    public function getCapital(): int
    {
        return $this->capital;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return array<string, int|string>
     */
    public function toArray(): array
    {
        return [
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'email' => $this->getEmail(),
            'password' => $this->getPassword(),
            'capital' => $this->getCapital()
        ];
    }
}
