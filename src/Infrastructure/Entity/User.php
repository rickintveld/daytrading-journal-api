<?php

namespace App\Infrastructure\Entity;

use App\Infrastructure\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements PasswordAuthenticatedUserInterface, UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string")
     */
    private string $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $lastName;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $blocked;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $removed;

    /**
     * @Ignore
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $createdAt;

    /**
     * @Ignore
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity=UserSettings::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private ?UserSettings $userSettings;

    /**
     * @ORM\OneToMany(targetEntity=Profit::class, mappedBy="user", cascade={"persist"})
     */
    private ?Collection $profits;

    public function __construct()
    {
        $this->profits = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
        $this->blocked = false;
        $this->removed = false;
    }

    public function getId(): string|null
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): string|null
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string|null
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getFirstName(): string|null
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string|null
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBlocked(): bool
    {
        return $this->blocked;
    }

    public function setBlocked(bool $blocked): self
    {
        $this->blocked = $blocked;

        return $this;
    }

    public function getRemoved(): bool
    {
        return $this->removed;
    }

    public function setRemoved(bool $removed): self
    {
        $this->removed = $removed;

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUserSettings(): UserSettings|null
    {
        return $this->userSettings;
    }

    public function setUserSettings(UserSettings|null $userSettings): self
    {
        if ($userSettings === null && $this->userSettings !== null) {
            $this->userSettings->setUser(null);
        }

        if ($userSettings !== null && $userSettings->getUser() !== $this) {
            $userSettings->setUser($this);
        }

        $this->userSettings = $userSettings;

        return $this;
    }

    public function getProfits(): Collection
    {
        return $this->profits;
    }

    public function addProfit(Profit $profit): self
    {
        if (!$this->profits->contains($profit)) {
            $this->profits->add($profit);
            $profit->setUser($this);
        }

        return $this;
    }

    public function removeProfit(Profit $profit): self
    {
        if ($this->profits->removeElement($profit) && $profit->getUser() === $this) {
            $profit->setUser(null);
        }

        return $this;
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
        $this->password = null;
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
