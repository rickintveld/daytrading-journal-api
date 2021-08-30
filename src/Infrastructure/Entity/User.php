<?php

namespace App\Infrastructure\Entity;

use App\Common\Exception\InvalidFundsException;
use App\Infrastructure\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $blocked;

    /**
     * @ORM\Column(type="boolean")
     */
    private $removed;

    /**
     * @Ignore
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @Ignore
     * @ORM\Column(type="datetime_immutable")
     */
    private $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity=UserSettings::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $userSettings;

    /**
     * @ORM\OneToMany(targetEntity=Profit::class, mappedBy="user", cascade={"persist"})
     */
    private $profits;

    public function __construct()
    {
        $this->profits = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return \App\Infrastructure\Entity\User
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return \App\Infrastructure\Entity\User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return \App\Infrastructure\Entity\User
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return \App\Infrastructure\Entity\User
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getBlocked(): ?bool
    {
        return $this->blocked;
    }

    /**
     * @param bool $blocked
     * @return \App\Infrastructure\Entity\User
     */
    public function setBlocked(bool $blocked): self
    {
        $this->blocked = $blocked;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getRemoved(): ?bool
    {
        return $this->removed;
    }

    /**
     * @param bool $removed
     * @return \App\Infrastructure\Entity\User
     */
    public function setRemoved(bool $removed): self
    {
        $this->removed = $removed;

        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeImmutable $createdAt
     * @return \App\Infrastructure\Entity\User
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeImmutable $updatedAt
     * @return \App\Infrastructure\Entity\User
     */
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return \App\Infrastructure\Entity\UserSettings|null
     */
    public function getUserSettings(): ?UserSettings
    {
        return $this->userSettings;
    }

    /**
     * @param \App\Infrastructure\Entity\UserSettings|null $userSettings
     * @return \App\Infrastructure\Entity\User
     */
    public function setUserSettings(?UserSettings $userSettings): self
    {
        // unset the owning side of the relation if necessary
        if ($userSettings === null && $this->userSettings !== null) {
            $this->userSettings->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($userSettings !== null && $userSettings->getUser() !== $this) {
            $userSettings->setUser($this);
        }

        $this->userSettings = $userSettings;

        return $this;
    }

    /**
     * @return Collection|Profit[]
     */
    public function getProfits(): Collection
    {
        return $this->profits;
    }

    /**
     * @param \App\Infrastructure\Entity\Profit $profit
     * @return \App\Infrastructure\Entity\User
     */
    public function addProfit(Profit $profit): self
    {
        if (!$this->profits->contains($profit)) {
            $this->profits[] = $profit;
            $profit->setUser($this);
        }

        return $this;
    }

    /**
     * @param \App\Infrastructure\Entity\Profit $profit
     * @return \App\Infrastructure\Entity\User
     */
    public function removeProfit(Profit $profit): self
    {
        if ($this->profits->removeElement($profit)) {
            // set the owning side to null (unless already changed)
            if ($profit->getUser() === $this) {
                $profit->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @param float $amount
     * @return \App\Infrastructure\Entity\User
     * @throws \App\Common\Exception\InvalidFundsException
     */
    public function withdraw(float $amount): self
    {
        if ($this->getUserSettings()->getCapital() === null) {
            throw new InvalidFundsException(sprintf('Not enough money to withdraw %s', $amount));
        }

        if ($this->getUserSettings()->getCapital() < $amount) {
            throw new InvalidFundsException(sprintf('Not enough money to withdraw %s', $amount));
        }

        $this->getProfits()->add((new Profit())->setAmount($amount));

        return $this;
    }

    public function block(): void
    {
        if ($this->getRemoved()) {
            throw new \Error('The user can not be blocked because of remove state');
        }

        $this->setBlocked(true);
    }

    public function unblock(): void
    {
        if ($this->getRemoved()) {
            throw new \Error('The user can not be unblocked because of remove state, user should be restored');
        }

        $this->setBlocked(false);
    }

    /**
     * @throws \Exception
     */
    public function remove(): void
    {
        if (!$this->getBlocked()) {
            throw new \Exception('This user can not be removed, should be blocked first');
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
        if (!$this->isEqualTo($capital, $this->getUserSettings()->getCapital())) {
            $this->getUserSettings()->setCapital($capital);
        }
    }

    /**
     * @param $value
     * @param $match
     * @return bool
     */
    protected function isEqualTo($value, $match): bool
    {
        return $value === $match;
    }

}
