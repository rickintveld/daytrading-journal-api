<?php

namespace App\Application\QueryHandler;

use App\Application\Query\AllUsersQuery;
use App\Common\Exception\UserNotFoundException;
use App\Domain\Contracts\Repository\UserRepository;
use App\Domain\Model\User;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\HandleTrait;

/**
 * @package App\Application\QueryHandler
 */
class AllUserQueryHandler implements MessageHandlerInterface
{
    use HandleTrait;

    public function __construct(private UserRepository $userRepository)
    {
    }

    /**
     * @return array<User>
     *
     * @throws UserNotFoundException
     */
    public function __invoke(AllUsersQuery $query): array
    {
        return $this->query($query);
    }

    /**
     * @return array<User>
     *
     * @throws UserNotFoundException
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
