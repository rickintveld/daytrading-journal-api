<?php

namespace App\Application\CommandHandler;

use App\Application\Command\UpdateUserCommand;
use App\Common\Exception\UserNotFoundException;
use App\Common\Interfaces\CommandHandler;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;

/**
 * @package App\Application\CommandHandler
 */
class UpdateUserCommandHandler implements CommandHandler
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
     * @param \App\Application\Command\UpdateUserCommand $command
     * @throws \App\Common\Exception\UserNotFoundException
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function __invoke(UpdateUserCommand $command): void
    {
        $user = $this->userRepository->findOneByIdentifier($command->getIdentifier());

        if (!$user) {
            throw new UserNotFoundException('No user found');
        }

        $this->handle($command, $user);
    }

    /**
     * @param \App\Application\Command\UpdateUserCommand $command
     * @param \App\Domain\Model\User                     $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function handle(UpdateUserCommand $command, User $user): void
    {
        $user->update(
            $command->getFirstName(),
            $command->getLastName(),
            $command->getEmail(),
            $command->getPassword(),
            $command->getCapital()
        );

        $this->userRepository->store($user);
    }
}
