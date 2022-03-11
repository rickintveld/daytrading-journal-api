<?php

namespace App\Application\Command;

use App\Common\Contracts\Command;

class CreateUserCommand implements Command
{
    public function __construct(private string $email, private string $firstName, private string $lastName, private int $capital, private string $password)
    {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getCapital(): int
    {
        return $this->capital;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function toArray(): array
    {
        return [
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'email' => $this->getEmail(),
            'password' => $this->getPassword(),
            'capital' => $this->getCapital(),
        ];
    }
}
