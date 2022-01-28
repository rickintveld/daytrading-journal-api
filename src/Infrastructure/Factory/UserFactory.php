<?php

namespace App\Infrastructure\Factory;

use App\Domain\Model\Profit;
use App\Domain\Model\User as DomainUser;
use App\Infrastructure\Entity\User;
use App\Infrastructure\Entity\UserSettings;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @package App\Infrastructure\Factory
 */
class UserFactory
{
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * @param \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @param \App\Infrastructure\Entity\User $user
     *
     * @return \App\Domain\Model\User
     *
     * @throws \Exception
     */
    public function toDomainUser(User $user): DomainUser
    {
        return new DomainUser(
            $user->getId(),
            $user->getEmail(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->getUserSettings() ? $user->getUserSettings()->getCapital() : 0,
            $user->getPassword(),
            $user->getProfits()->toArray(),
            $user->getBlocked(),
            $user->getRemoved(),
            $user->getCreatedAt(),
            $user->getUpdatedAt()
        );
    }

    /**
     * @param \App\Domain\Model\User $domainUser
     *
     * @return \App\Infrastructure\Entity\User
     */
    public function toPersistence(DomainUser $domainUser): User
    {
        if (null === $domainUser->getPassword()) {
            return $this->createNewUser($domainUser);
        }

        $user = new User();
        $user->setId($domainUser->getId());
        $user->setFirstName($domainUser->getFirstName());
        $user->setLastName($domainUser->getLastName());
        $user->setEmail($domainUser->getEmail());
        $user->setRemoved($domainUser->isRemoved());
        $user->setBlocked($domainUser->isBlocked());

        if ($this->passwordHasher->isPasswordValid($user, $domainUser->getPassword())) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $domainUser->getPassword()));
        } else {
            $user->setPassword($domainUser->getPassword());
        }

        $userSettings = new UserSettings();
        $userSettings->setCapital($domainUser->getCapital());
        $userSettings->setUser($user);

        $user->setUserSettings($userSettings);

        if ($domainUser->hasProfits()) {
            array_map(static function (Profit $profit) use($user) {
                $newProfit = new \App\Infrastructure\Entity\Profit();
                $newProfit->setUser($user);
                $newProfit->setAmount($profit->getAmount());
                $user->addProfit($newProfit);
            }, $domainUser->getProfits());
        }

        return $user;
    }

    /**
     * @param \App\Domain\Model\User $domainUser
     * @return \App\Infrastructure\Entity\User
     */
    public function createNewUser(DomainUser $domainUser): User
    {
        $user = new User();

        $user->setId($domainUser->getId());
        $user->setFirstName($domainUser->getFirstName());
        $user->setLastName($domainUser->getLastName());
        $user->setEmail($domainUser->getEmail());
        $user->setRemoved($domainUser->isRemoved());
        $user->setBlocked($domainUser->isBlocked());
        $user->setPassword($domainUser->getPassword());

        $userSettings = new UserSettings();
        $userSettings->setCapital($domainUser->getCapital());
        $userSettings->setUser($user);

        $user->setUserSettings($userSettings);

        return $user;
    }
}
