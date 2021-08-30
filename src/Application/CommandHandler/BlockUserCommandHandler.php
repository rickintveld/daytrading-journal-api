<?php

namespace App\Application\CommandHandler;

use App\Application\Command\BlockUserCommand;
use App\Common\Exception\UserNotFoundException;
use App\Common\Interfaces\CommandHandler;
use App\Infrastructure\Entity\User;
use App\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @package App\Application\CommandHandler
 */
class BlockUserCommandHandler implements CommandHandler
{
    /** @var \App\Infrastructure\Repository\UserRepository */
    private $userRepository;

    /** @var \Doctrine\ORM\EntityManager */
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
     * @param \App\Application\Command\BlockUserCommand $command
     * @throws \Exception
     */
    public function __invoke(BlockUserCommand $command): void
    {
        $user = $this->userRepository->findOneBy(['id' => $command->getIdentifier()]);

        if (!$user) {
            throw new UserNotFoundException('No user found');
        }

        $this->handle($user);
    }

    /**
     * @param \App\Infrastructure\Entity\User $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function handle(User $user): void
    {
        $user->block();

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
