<?php

namespace App\Application\QueryHandler;

use App\Application\Query\AllUsersQuery;
use App\Common\Exception\UserNotFoundException;
use App\Domain\Model\User as DomainUser;
use App\Infrastructure\Repository\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\HandleTrait;

/**
 * @package App\Application\QueryHandler
 */
class AllUserQueryHandler implements MessageHandlerInterface
{
    use HandleTrait;

    private UserRepository $userRepository;

    /**
     * @param \App\Infrastructure\Repository\UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param \App\Application\Query\AllUsersQuery $query
     * @return \App\Domain\Model\User[]
     *
     * @throws \App\Common\Exception\UserNotFoundException
     */
    public function __invoke(AllUsersQuery $query)
    {
        return $this->query($query);
    }

    /**
     * @param \App\Application\Query\AllUsersQuery $query
     * @return \App\Domain\Model\User[]
     *
     * @throws \App\Common\Exception\UserNotFoundException
     */
    private function query(AllUsersQuery $query): array
    {
        $users = $this->userRepository->findAllActive($query->isBlocked(), $query->isRemoved());

        if (!$users) {
            throw new UserNotFoundException('No users found');
        }

        return $users;
    }
}
