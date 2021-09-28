<?php

namespace App\Domain\Model;

use App\Common\Exception\InvalidFundsException;
use App\Common\Exception\InvalidUserStateException;

/**
 * @package App\Domain\Model
 */
class User
{
    /** @var int */
    private $id;

    /** @var string */
    private $email;

    /** @var string */
    private $password;

    /** @var string */
    private $firstName;

    /** @var string */
    private $lastName;

    /** @var float */
    private $startCapital;

    /** @var \App\Domain\Model\Profit[] */
    private $profits;

    /** @var bool */
    private $blocked;

    /** @var bool */
    private $removed;

    /** @var \DateTimeImmutable */
    private $createdAt;

    /** @var \DateTimeImmutable */
    private $updatedAt;

    /**
     * User constructor.
     * @param int                        $id
     * @param string                     $email
     * @param string                     $password
     * @param string                     $firstName
     * @param string                     $lastName
     * @param float                      $capital
     * @param \App\Domain\Model\Profit[] $profits
     * @param bool                       $blocked
     * @param bool                       $removed
     * @param \DateTimeImmutable         $createdAt
     * @param \DateTimeImmutable         $updatedAt
     * @throws \Exception
     */
    public function __construct(
        int $id,
        string $email,
        string $password,
        string $firstName,
        string $lastName,
        float $capital,
        array $profits,
        bool $blocked = false,
        bool $removed = false,
        \DateTimeImmutable $createdAt = null,
        \DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->startCapital = $capital;
        $this->profits = $profits;
        $this->blocked = $blocked;
        $this->removed = $removed;
        $this->createdAt = $createdAt ?: new \DateTimeImmutable();
        $this->updatedAt = $updatedAt ?: new \DateTimeImmutable();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return float
     */
    public function getStartCapital(): float
    {
        return $this->startCapital;
    }

    /**
     * @param float $startCapital
     */
    public function setStartCapital(float $startCapital): void
    {
        $this->startCapital = $startCapital;
    }

    /**
     * @return \App\Domain\Model\Profit[]
     */
    public function getProfits(): array
    {
        return $this->profits;
    }

    /**
     * @param \App\Domain\Model\Profit[] $profits
     */
    public function setProfits(array $profits): void
    {
        $this->profits = $profits;
    }

    /**
     * @return bool
     */
    public function isBlocked(): bool
    {
        return $this->blocked;
    }

    /**
     * @param bool $blocked
     */
    public function setBlocked(bool $blocked): void
    {
        $this->blocked = $blocked;
    }

    /**
     * @return bool
     */
    public function isRemoved(): bool
    {
        return $this->removed;
    }

    /**
     * @param bool $removed
     */
    public function setRemoved(bool $removed): void
    {
        $this->removed = $removed;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable $createdAt
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeImmutable $updatedAt
     */
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @param float $amount
     * @return \App\Domain\Model\User
     * @throws \Exception
     */
    public function withdraw(float $amount): self
    {
        if (!$this->getStartCapital()) {
            throw new InvalidFundsException(sprintf('Not enough money to withdraw %s', $amount));
        }
        if ($this->getStartCapital() < $amount) {
            throw new InvalidFundsException(sprintf('Not enough money to withdraw %s', $amount));
        }

        $this->setStartCapital($this->getStartCapital() - $amount);

        return $this;
    }

    /**
     * @throws \App\Common\Exception\InvalidUserStateException
     */
    public function block(): void
    {
        if ($this->isRemoved()) {
            throw new InvalidUserStateException('The user can not be blocked because of remove state');
        }

        $this->setBlocked(true);
    }

    /**
     * @throws \App\Common\Exception\InvalidUserStateException
     */
    public function unblock(): void
    {
        if ($this->isRemoved()) {
            throw new InvalidUserStateException(
                'The user can not be unblocked because of remove state, user should be restored'
            );
        }

        $this->setBlocked(false);
    }

    /**
     * @throws \App\Common\Exception\InvalidUserStateException
     */
    public function remove(): void
    {
        if (!$this->isBlocked()) {
            throw new InvalidUserStateException('This user can not be removed, should be blocked first');
        }

        $this->setRemoved(true);
    }

    public function restore(): void
    {
        $this->setBlocked(false);
        $this->setRemoved(false);
    }

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $password
     * @param float  $capital
     */
    public function update(string $firstName, string $lastName, string $email, string $password, float $capital): void
    {
        if (!$this->isEqualTo($firstName, $this->getFirstName())) {
            $this->setFirstName($firstName);
        }
        if (!$this->isEqualTo($lastName, $this->getLastName())) {
            $this->setLastName($lastName);
        }
        if (!$this->isEqualTo($email, $this->getEmail())) {
            $this->setEmail($email);
        }
        if (!$this->isEqualTo($password, $this->getPassword())) {
            $this->setPassword($password);
        }
        if (!$this->isEqualTo($capital, $this->getStartCapital())) {
            $this->setStartCapital($capital);
        }
    }

    /**
     * @param int|string|float|object $value
     * @param int|string|float|object $match
     * @return bool
     */
    protected function isEqualTo($value, $match): bool
    {
        return $value === $match;
    }

    public function getFullName(): string
    {
        return implode(' ', [
            $this->getFirstName(),
            $this->getLastName()
        ]);
    }

    /**
     * @return float
     */
    public function getCapital(): float
    {
        $startCapital = $this->getStartCapital();
        $profits = array_map(
            /** Profit $profit */
            static function ($profit) {
                return $profit->getAmount();
            },
            $this->getProfits()
        );

        return $startCapital + array_sum($profits);
    }
}
