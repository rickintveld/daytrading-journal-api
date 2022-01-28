<?php

namespace App\Infrastructure\Repository;

use App\Common\Exception\UserNotFoundException;
use App\Domain\Contracts\Repository\UserRepository as UserRepositoryInterface;
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
class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    private UserFactory $userFactory;

    /**
     * UserRepository constructor.
     *
     * @param \Doctrine\Persistence\ManagerRegistry   $registry
     * @param \App\Infrastructure\Factory\UserFactory $userFactory
     */
    public function __construct(ManagerRegistry $registry, UserFactory $userFactory)
    {
        parent::__construct($registry, User::class);
        $this->userFactory = $userFactory;
    }

    /**
     * @param string $identifier
     *
     * @return \App\Domain\Model\User
     *
     * @throws \App\Common\Exception\UserNotFoundException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByIdentifier(string $identifier): DomainUser
    {
        $user = $this->createQueryBuilder('u')
            ->where('u.id = :id')
            ->andWhere('u.blocked', false)
            ->andWhere('u.removed', false)
            ->setParameter('id', $identifier)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$user) {
            throw new UserNotFoundException(sprintf('User not found with id: %s', $identifier));
        }

        return $this->userFactory->toDomainUser($user);
    }

    /**
     * @param string $email
     *
     * @return \App\Domain\Model\User|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByEmail(string $email): ?DomainUser
    {
        $user = $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->andWhere('u.blocked', false)
            ->andWhere('u.removed', false)
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();

        return $user ? $this->userFactory->toDomainUser($user) : $user;
    }

    /**
     * @param string $email
     * @param string $password
     *
     * @return \App\Domain\Model\User|null
     *
     * @throws \App\Common\Exception\UserNotFoundException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByEmailAndPassword(string $email, string $password): ?DomainUser
    {
        $user = $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->andWhere('u.password = :password')
            ->andWhere('u.blocked', false)
            ->andWhere('u.removed', false)
            ->setParameters([
                'email' => $email,
                'password' => $password
            ])
            ->getQuery()
            ->getOneOrNullResult();

        if (!$user) {
            throw new UserNotFoundException(sprintf('User not found with e-mail: %s', $email));
        }

        return $this->userFactory->toDomainUser($user);
    }

    /**
     * @param bool $isBlocked
     * @param bool $isRemoved
     *
     * @return \App\Domain\Model\User[]
     * @throws \Exception
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
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function store(DomainUser $user): void
    {
        $this->getEntityManager()->persist($this->userFactory->toPersistence($user));
        $this->getEntityManager()->flush();
    }
}
