<?php

namespace App\Infrastructure\Factory;

use App\Domain\Model\Profit;
use App\Domain\Model\User as DomainUser;
use App\Infrastructure\Entity\User;
use App\Infrastructure\Entity\UserSettings;

/**
 * @package App\Infrastructure\Factory
 */
class UserFactory
{
    /**
     * @param \App\Infrastructure\Entity\User $user
     * @return \App\Domain\Model\User
     * @throws \Exception
     */
    public function toDomainUser(User $user): DomainUser
    {
        return new DomainUser(
            $user->getId(),
            $user->getEmail(),
            $user->getPassword(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->getUserSettings() ? $user->getUserSettings()->getCapital() : 0,
            $user->getProfits()->toArray(),
            $user->getBlocked(),
            $user->getRemoved(),
            $user->getCreatedAt(),
            $user->getUpdatedAt()
        );
    }

    public function toPersistence(DomainUser $domainUser): User
    {
        $user = new User();

        $user->setId($domainUser->getId());
        $user->setFirstName($domainUser->getFirstName());
        $user->setLastName($domainUser->getLastName());
        $user->setEmail($domainUser->getEmail());
        $user->setRemoved($domainUser->isRemoved());
        $user->setBlocked($domainUser->isBlocked());

        $userSettings = new UserSettings();
        $userSettings->setCapital($domainUser->getCapital());
        $userSettings->setUser($user);

        $user->setUserSettings($userSettings);

        array_map(static function (Profit $profit) use($user) {
            $newProfit = new \App\Infrastructure\Entity\Profit();
            $newProfit->setUser($user);
            $newProfit->setAmount($profit->getAmount());
            $user->addProfit($newProfit);
        }, $domainUser->getProfits());

        return $user;
    }
}
