<?php

namespace App\Infrastructure\Factory;

use App\Domain\Model\User as DomainUser;
use App\Infrastructure\Entity\User;

/**
 * @package App\Infrastructure\Factory
 */
class UserFactory
{
    /**
     * @param \App\Infrastructure\Entity\User $user
     * @return \App\Domain\Model\User
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
}
