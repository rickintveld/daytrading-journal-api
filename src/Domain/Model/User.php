<?php

namespace App\Domain\Model;

use App\Common\Exception\InvalidFundsException;
use App\Common\Exception\InvalidUserStateException;
use App\Domain\Contracts\Analyse\Analysable;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements Analysable, UserInterface, PasswordAuthenticatedUserInterface
{
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        private string $id,
        private string $email,
        private string $firstName,
        private string $lastName,
        private float $startCapital,
        private string|null $password = null,
        private array|null $profits = null,
        private bool $blocked = false,
        private bool $removed = false,
        \DateTimeImmutable $createdAt = null,
        \DateTimeImmutable $updatedAt = null
    ) {
        $this->createdAt = $createdAt ?: new \DateTimeImmutable();
        $this->updatedAt = $updatedAt ?: new \DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string|null $password): void
    {
        $this->password = $password;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getStartCapital(): float
    {
        return $this->startCapital;
    }

    public function setStartCapital(float $startCapital): void
    {
        $this->startCapital = $startCapital;
    }

    public function hasProfits(): bool
    {
        return (null !== $this->getProfits()) || (is_array($this->getProfits()) ? count($this->getProfits()) > 0 : false);
    }

    public function getProfits(): ?array
    {
        return $this->profits;
    }

    public function setProfits(array $profits): void
    {
        $this->profits = $profits;
    }

    public function addProfit(Profit $profit): void
    {
        $this->profits = array_merge($this->getProfits(), [$profit]);
    }

    public function isBlocked(): bool
    {
        return $this->blocked;
    }

    public function setBlocked(bool $blocked): void
    {
        $this->blocked = $blocked;
    }

    public function isRemoved(): bool
    {
        return $this->removed;
    }

    public function setRemoved(bool $removed): void
    {
        $this->removed = $removed;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @throws InvalidFundsException
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
     * @throws InvalidUserStateException
     */
    public function block(): void
    {
        if ($this->isRemoved()) {
            throw new InvalidUserStateException('The user can not be blocked because of remove state');
        }

        $this->setBlocked(true);
    }

    /**
     * @throws InvalidUserStateException
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
     * @throws InvalidUserStateException
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

    protected function isEqualTo(mixed $value, mixed $match): bool
    {
        return $value === $match;
    }

    public function getFullName(): string
    {
        return implode(' ', [
            $this->getFirstName(),
            $this->getLastName(),
        ]);
    }

    public function getCapital(): float
    {
        $profits = [];
        $startCapital = $this->getStartCapital();

        if ($this->hasProfits()) {
            $profits = array_map(static function ($profit) {
                return $profit->getAmount();
            }, $this->getProfits());
        }

        return $startCapital + array_sum($profits);
    }

    public function getRoles(): array
    {
        return [];
    }

    public function getSalt(): string
    {
        return $this->getPassword();
    }

    public function eraseCredentials(): void
    {
        $this->setPassword(null);
    }

    public function getUsername(): string
    {
        return $this->getEmail();
    }

    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }
}
