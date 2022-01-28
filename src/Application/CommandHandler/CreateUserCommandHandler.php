<?php

namespace App\Application\CommandHandler;

use App\Application\Command\CreateUserCommand;
use App\Common\Contracts\CommandHandler;
use App\Common\Exception\UserAlreadyExists;
use App\Domain\Builder\UserBuilder;
use App\Domain\Contracts\Repository\UserRepository;

/**
 * @package App\Application\CommandHandler
 */
class CreateUserCommandHandler implements CommandHandler
{
    private UserRepository $userRepository;
    private UserBuilder $userBuilder;

    /**
     * @param \App\Domain\Contracts\Repository\UserRepository $userRepository
     * @param \App\Domain\Builder\UserBuilder                 $userBuilder
     */
    public function __construct(UserRepository $userRepository, UserBuilder $userBuilder)
    {
        $this->userRepository = $userRepository;
        $this->userBuilder = $userBuilder;
    }

    /**
     * @param \App\Application\Command\CreateUserCommand $command
     *
     * @throws \App\Common\Exception\UserAlreadyExists
     * @throws \App\Common\Exception\UserNotFoundException
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function __invoke(CreateUserCommand $command): void
    {
        $user = $this->userRepository->findOneByEmail($command->getEmail());

        if ($user) {
            throw new UserAlreadyExists(sprintf('User with e-mail %s already exists', $command->getEmail()));
        }

        $this->handle($command);
    }

    /**
     * @param \App\Application\Command\CreateUserCommand $command
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    private function handle(CreateUserCommand $command): void
    {
        $this->userRepository->store($this->userBuilder->build($command->toArray()));
    }
}
