<?php

namespace App\Application\CommandHandler;

use App\Application\Command\WithdrawCommand;
use App\Common\Exception\UserNotFoundException;
use App\Common\Interfaces\CommandHandler;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;

/**
 * @package App\Application\CommandHandler
 */
class WithdrawCommandHandler implements CommandHandler
{
    private UserRepository $userRepository;

    /**
     * @param \App\Domain\Repository\UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param \App\Application\Command\WithdrawCommand $command
     * @throws \Exception
     */
    public function __invoke(WithdrawCommand $command): void
    {
        $user = $this->userRepository->findOneByIdentifier($command->getUserId());

        if (!$user) {
            throw new UserNotFoundException(sprintf('No user found for id %s', $command->getUserId()));
        }

        $this->handle($command, $user);
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
