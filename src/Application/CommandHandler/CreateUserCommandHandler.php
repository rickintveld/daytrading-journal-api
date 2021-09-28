<?php

namespace App\Application\CommandHandler;

use App\Application\Command\CreateUserCommand;
use App\Common\Exception\UserAlreadyExists;
use App\Common\Exception\UserNotFoundException;
use App\Common\Interfaces\CommandHandler;
use App\Infrastructure\Builder\UserBuilder;
use App\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @package App\Application\CommandHandler
 */
class CreateUserCommandHandler implements CommandHandler
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private UserBuilder $userBuilder;

    /**
     * @param \App\Infrastructure\Repository\UserRepository $userRepository
     * @param \Doctrine\ORM\EntityManagerInterface          $entityManager
     * @param \App\Infrastructure\Builder\UserBuilder       $userBuilder
     */
    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        UserBuilder $userBuilder
    ) {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->userBuilder = $userBuilder;
    }

    /**
     * @param \App\Application\Command\CreateUserCommand $command
     * @throws \App\Common\Exception\UserAlreadyExists
     */
    public function __invoke(CreateUserCommand $command): void
    {
        $user = $this->userRepository->findOneBy(['email' => $command->getEmail()]);

        if ($user) {
            throw new UserAlreadyExists('User already exists');
        }

        $this->handle($command);
    }

    /**
     * @param \App\Application\Command\CreateUserCommand $command
     */
    private function handle(CreateUserCommand $command): void
    {
        $this->entityManager->persist($this->userBuilder->build($command->toArray()));
        $this->entityManager->flush();
    }
}
