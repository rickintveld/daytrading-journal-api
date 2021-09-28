<?php

namespace App\Application\CommandHandler;

use App\Application\Command\RemoveUserCommand;
use App\Common\Exception\UserNotFoundException;
use App\Common\Interfaces\CommandHandler;
use App\Infrastructure\Entity\User;
use App\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @package App\Application\CommandHandler
 */
class RemoveUserCommandHandler implements CommandHandler
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

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
     * @param \App\Application\Command\RemoveUserCommand $command
     * @throws \Exception
     */
    public function __invoke(RemoveUserCommand $command): void
    {
        $user = $this->userRepository->findOneBy(['id' => $command->getIdentifier()]);

        if (!$user) {
            throw new UserNotFoundException('No user found');
        }

        $this->handle($user);
    }

    /**
     * @param \App\Infrastructure\Entity\User $user
     * @throws \Exception
     */
    private function handle(User $user): void
    {
        $user->remove();

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
