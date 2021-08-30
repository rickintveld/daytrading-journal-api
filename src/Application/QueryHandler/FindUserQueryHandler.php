<?php

namespace App\Application\QueryHandler;

use App\Application\Query\FindUserQuery;
use App\Common\Exception\UserNotFoundException;
use App\Domain\Model\User as DomainUser;
use App\Infrastructure\Repository\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\HandleTrait;

/**
 * @package App\Application\QueryHandler
 */
class FindUserQueryHandler implements MessageHandlerInterface
{
    use HandleTrait;

    /** @var \App\Infrastructure\Repository\UserRepository */
    private $userRepository;

    /**
     * @param \App\Infrastructure\Repository\UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param \App\Application\Query\FindUserQuery $query
     * @return \App\Domain\Model\User
     *
     * @throws \App\Common\Exception\UserNotFoundException
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function __invoke(FindUserQuery $query)
    {
        return $this->query($query);
    }

    /**
     * @param \App\Application\Query\FindUserQuery $query
     * @return \App\Domain\Model\User
     *
     * @throws \App\Common\Exception\UserNotFoundException
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function query(FindUserQuery $query): DomainUser
    {
        $user = $this->userRepository->findOneByIdentifier($query->getUserId());

        if (!$user) {
            throw new UserNotFoundException(sprintf('No user found for id %s', $query->getUserId()));
        }

        return $user;
    }
}
