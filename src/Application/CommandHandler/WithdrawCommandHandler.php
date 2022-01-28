<?php

namespace App\Application\CommandHandler;

use App\Application\Command\WithdrawCommand;
use App\Common\Contracts\CommandHandler;
use App\Common\Exception\UserNotFoundException;
use App\Domain\Contracts\Repository\UserRepository;
use App\Domain\Model\User;

/**
 * @package App\Application\CommandHandler
 */
class WithdrawCommandHandler implements CommandHandler
{
    private UserRepository $userRepository;

    /**
     * @param \App\Domain\Contracts\Repository\UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param \App\Application\Command\WithdrawCommand $command
     *
     * @throws \App\Common\Exception\InvalidFundsException
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function __invoke(WithdrawCommand $command): void
    {
        try {
            $user = $this->userRepository->findOneByIdentifier($command->getUserId());
            $this->handle($command, $user);
        } catch (UserNotFoundException $exception) {

        }
    }

    /**
     * @param \App\Application\Command\WithdrawCommand $command
     * @param \App\Domain\Model\User                   $user
     * @throws \App\Common\Exception\InvalidFundsException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function handle(WithdrawCommand $command, User $user): void
    {
        $user->withdraw($command->getAmount());

        $this->userRepository->store($user);
    }
}
