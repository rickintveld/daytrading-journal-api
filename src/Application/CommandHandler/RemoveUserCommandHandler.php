<?php

namespace App\Application\CommandHandler;

use App\Application\Command\RemoveUserCommand;
use App\Common\Exception\UserNotFoundException;
use App\Common\Interfaces\CommandHandler;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;

/**
 * @package App\Application\CommandHandler
 */
class RemoveUserCommandHandler implements CommandHandler
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
     * @param \App\Application\Command\RemoveUserCommand $command
     * @throws \Exception
     */
    public function __invoke(RemoveUserCommand $command): void
    {
        $user = $this->userRepository->findOneByIdentifier($command->getIdentifier());

        if (!$user) {
            throw new UserNotFoundException('No user found');
        }

        $this->handle($user);
    }

    /**
     * @param \App\Domain\Model\User $user
     * @throws \App\Common\Exception\InvalidUserStateException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function handle(User $user): void
    {
        $user->remove();

        $this->userRepository->store($user);
    }
}
