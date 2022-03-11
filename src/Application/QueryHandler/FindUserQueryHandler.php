<?php

namespace App\Application\QueryHandler;

use App\Application\Query\FindUserQuery;
use App\Domain\Contracts\Repository\UserRepository;
use App\Domain\Model\User;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\HandleTrait;

class FindUserQueryHandler implements MessageHandlerInterface
{
    use HandleTrait;

    public function __construct(private UserRepository $userRepository)
    {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(FindUserQuery $query): User
    {
        return $this->query($query);
    }

    /**
     * @throws \Exception
     */
    private function query(FindUserQuery $query): User
    {
        return $this->userRepository->findOneByIdentifier($query->getUserId());
    }
}
