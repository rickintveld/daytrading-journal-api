<?php

namespace App\Domain\Contracts\Repository;

use App\Domain\Model\User;

interface UserRepository
{
    /**
     * @param string $identifier
     *
     * @return \App\Domain\Model\User
     *
     * @throws \App\Common\Exception\UserNotFoundException
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByIdentifier(string $identifier): User;

    /**
     * @param string $email
     *
     * @return \App\Domain\Model\User|null
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \App\Common\Exception\UserNotFoundException
     */
    public function findOneByEmail(string $email): ?User;

    /**
     * @param string $email
     * @param string $password
     *
     * @return \App\Domain\Model\User|null
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \App\Common\Exception\UserNotFoundException
     */
    public function findByEmailAndPassword(string $email, string $password): ?User;

    /**
     * @param bool $isBlocked
     * @param bool $isRemoved
     *
     * @return \App\Domain\Model\User[]
     */
    public function findAllActive(bool $isBlocked, bool $isRemoved): array;

    /**
     * @param \App\Domain\Model\User $user
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function store(User $user): void;
}
