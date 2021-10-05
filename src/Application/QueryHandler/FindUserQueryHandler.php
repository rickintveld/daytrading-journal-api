<?php

namespace App\Application\QueryHandler;

use App\Application\Query\FindUserQuery;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\HandleTrait;

/**
 * @package App\Application\QueryHandler
 */
class FindUserQueryHandler implements MessageHandlerInterface
{
    use HandleTrait;

    private UserRepository $userRepository;

    /**
     * @param \App\Domain\Repository\UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param \App\Application\Query\FindUserQuery $query
     * @return \App\Domain\Model\User
     *
     * @throws \Exception
     */
    public function __invoke(FindUserQuery $query)
    {
        return $this->query($query);
    }

    /**
     * @param \App\Application\Query\FindUserQuery $query
     * @return \App\Domain\Model\User
     *
     * @throws \Exception
     */
    private function query(FindUserQuery $query): User
    {
        return $this->userRepository->findOneByIdentifier($query->getUserId());
    }
}
