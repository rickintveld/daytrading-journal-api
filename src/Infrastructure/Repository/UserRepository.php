<?php

namespace App\Infrastructure\Repository;

use App\Common\Exception\UserNotFoundException;
use App\Domain\Contracts\Repository\UserRepository as UserRepositoryInterface;
use App\Domain\Model\User as DomainUser;
use App\Infrastructure\Entity\User;
use App\Infrastructure\Factory\UserFactory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry, private UserFactory $userFactory)
    {
        parent::__construct($registry, User::class);
    }

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
     * @throws NonUniqueResultException
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
     * @throws UserNotFoundException
     * @throws NonUniqueResultException
     */
    public function findByEmailAndPassword(string $email, string $password): DomainUser|null
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

    public function store(DomainUser $user): void
    {
        $this->getEntityManager()->persist($this->userFactory->toPersistence($user));
        $this->getEntityManager()->flush();
    }
}
