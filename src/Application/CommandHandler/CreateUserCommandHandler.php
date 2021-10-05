<?php

namespace App\Application\CommandHandler;

use App\Application\Command\CreateUserCommand;
use App\Common\Exception\UserAlreadyExists;
use App\Common\Interfaces\CommandHandler;
use App\Domain\Repository\UserRepository;
use App\Domain\Builder\UserBuilder;

/**
 * @package App\Application\CommandHandler
 */
class CreateUserCommandHandler implements CommandHandler
{
    private UserRepository $userRepository;
    private UserBuilder $userBuilder;

    /**
     * @param \App\Domain\Repository\UserRepository $userRepository
     * @param \App\Domain\Builder\UserBuilder       $userBuilder
     */
    public function __construct(UserRepository $userRepository, UserBuilder $userBuilder)
    {
        $this->userRepository = $userRepository;
        $this->userBuilder = $userBuilder;
    }

    /**
     * @param \App\Application\Command\CreateUserCommand $command
     * @throws \App\Common\Exception\UserAlreadyExists
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function __invoke(CreateUserCommand $command): void
    {
        $user = $this->userRepository->findOneByEmail($command->getEmail());

        if ($user) {
            throw new UserAlreadyExists('User already exists');
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
        $this->userRepository->store(
            $this->userBuilder->build($command->toArray())
        );
    }
}
