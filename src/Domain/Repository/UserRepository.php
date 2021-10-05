<?php

namespace App\Domain\Repository;

use App\Domain\Model\User;

/**
 * Interface UserRepository
 * @package App\Domain\Repository
 */
interface UserRepository
{
    /**
     * @param int $identifier
     * @return \App\Domain\Model\User
     *
     * @throws \App\Common\Exception\UserNotFoundException
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByIdentifier(int $identifier): User;

    /**
     * @param string $email
     * @return \App\Domain\Model\User
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByEmail(string $email): User;

    /**
     * @param bool $isBlocked
     * @param bool $isRemoved
     * @return \App\Domain\Model\User[]
     */
    public function findAllActive(bool $isBlocked, bool $isRemoved): array;

    /**
     * @param \App\Domain\Model\User $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function store(User $user): void;
}
