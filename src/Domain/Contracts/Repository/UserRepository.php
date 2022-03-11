<?php

namespace App\Domain\Contracts\Repository;

use App\Common\Exception\UserNotFoundException;
use App\Domain\Model\User;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

interface UserRepository
{
    /**
     * @throws UserNotFoundException
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findOneByIdentifier(string $identifier): User;

    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws UserNotFoundException
     */
    public function findOneByEmail(string $email): ?User;

    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws UserNotFoundException
     */
    public function findByEmailAndPassword(string $email, string $password): User|null;

    /**
     * @return array<User>
     */
    public function findAllActive(bool $isBlocked, bool $isRemoved): array;

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function store(User $user): void;
}
