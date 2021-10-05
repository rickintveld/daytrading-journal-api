<?php

namespace App\Infrastructure\Repository;

use App\Common\Exception\UserNotFoundException;
use App\Domain\Model\User as DomainUser;
use App\Infrastructure\Entity\User;
use App\Infrastructure\Factory\UserFactory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements \App\Domain\Repository\UserRepository
{
    private UserFactory $userFactory;

    /**
     * UserRepository constructor.
     * @param \Doctrine\Persistence\ManagerRegistry $registry
     * @param \App\Infrastructure\Factory\UserFactory $userFactory
     */
    public function __construct(ManagerRegistry $registry, UserFactory $userFactory)
    {
        parent::__construct($registry, User::class);
        $this->userFactory = $userFactory;
    }

    /**
     * @param int $identifier
     * @return \App\Domain\Model\User
     *
     * @throws \App\Common\Exception\UserNotFoundException
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByIdentifier(int $identifier): DomainUser
    {
        $user = $this->createQueryBuilder('u')
                     ->andWhere('u.id = :id')
                     ->setParameter('id', $identifier)
                     ->getQuery()
                     ->getSingleResult();

        if (!$user) {
            throw new UserNotFoundException(sprintf('User not found with id: %s', $identifier));
        }

        return $this->userFactory->toDomainUser($user);
    }

    /**
     * @param string $email
     * @return \App\Domain\Model\User
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByEmail(string $email): DomainUser
    {
        $user = $this->createQueryBuilder('u')
                     ->andWhere('u.email = :email')
                     ->setParameter('email', $email)
                     ->getQuery()
                     ->getSingleResult();

        return $this->userFactory->toDomainUser($user);
    }

    /**
     * @param bool $isBlocked
     * @param bool $isRemoved
     * @return \App\Domain\Model\User[]
     */
    public function findAllActive(bool $isBlocked, bool $isRemoved): array
    {
        $users = $this->createQueryBuilder('u')
                      ->andWhere('u.blocked = :blocked')
                      ->andWhere('u.removed = :removed')
                      ->setParameter('blocked', $isBlocked)
                      ->setParameter('removed', $isRemoved)
                      ->getQuery()
                      ->getResult();

        return array_map(
            function ($user) {
                return $this->userFactory->toDomainUser($user);
            },
            $users
        );
    }

    /**
     * @param \App\Domain\Model\User $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function store(DomainUser $user): void
    {
        $this->getEntityManager()->persist($this->userFactory->toPersistence($user));
        $this->getEntityManager()->flush();
    }
}
