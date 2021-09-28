<?php

namespace App\Application\CommandHandler;

use App\Application\Command\UpdateUserCommand;
use App\Common\Exception\UserNotFoundException;
use App\Common\Interfaces\CommandHandler;
use App\Infrastructure\Entity\User;
use App\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @package App\Application\CommandHandler
 */
class UpdateUserCommandHandler implements CommandHandler
{
    /** @var \App\Infrastructure\Repository\UserRepository */
    private $userRepository;

    /** @var \Doctrine\ORM\EntityManagerInterface */
    private $entityManager;

    /**
     * @param \App\Infrastructure\Repository\UserRepository $userRepository
     * @param \Doctrine\ORM\EntityManagerInterface          $entityManager
     */
    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param \App\Application\Command\UpdateUserCommand $command
     * @throws \App\Common\Exception\UserNotFoundException
     */
    public function __invoke(UpdateUserCommand $command): void
    {
        $user = $this->userRepository->findOneBy(['id' => $command->getIdentifier()]);

        if (!$user) {
            throw new UserNotFoundException('No user found');
        }

        $this->handle($command, $user);
    }

    /**
     * @param \App\Application\Command\UpdateUserCommand $command
     * @param \App\Infrastructure\Entity\User            $user
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

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
