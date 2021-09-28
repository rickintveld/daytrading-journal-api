<?php

namespace App\Application\CommandHandler;

use App\Application\Command\RestoreUserCommand;
use App\Common\Exception\UserNotFoundException;
use App\Common\Interfaces\CommandHandler;
use App\Infrastructure\Entity\User;
use App\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @package App\Application\CommandHandler
 */
class RestoreUserCommandHandler implements CommandHandler
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
     * @param \App\Application\Command\RestoreUserCommand $command
     * @throws \Exception
     */
    public function __invoke(RestoreUserCommand $command): void
    {
        $user = $this->userRepository->findOneBy(['id' => $command->getIdentifier()]);

        if (!$user) {
            throw new UserNotFoundException('No user found');
        }

        $this->handle($user);
    }

    /**
     * @param \App\Infrastructure\Entity\User $user
     */
    private function handle(User $user): void
    {
        $user->restore();

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
