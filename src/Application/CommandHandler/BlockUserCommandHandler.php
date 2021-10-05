<?php

namespace App\Application\CommandHandler;

use App\Application\Command\BlockUserCommand;
use App\Common\Exception\UserNotFoundException;
use App\Common\Interfaces\CommandHandler;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @package App\Application\CommandHandler
 */
class BlockUserCommandHandler implements CommandHandler
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    /**
     * @param \App\Domain\Repository\UserRepository $userRepository
     * @param \Doctrine\ORM\EntityManagerInterface  $entityManager
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
        $user = $this->userRepository->findOneByIdentifier($command->getIdentifier());

        if (!$user) {
            throw new UserNotFoundException('No user found');
        }

        $this->handle($user);
    }

    /**
     * @param \App\Domain\Model\User $user
     * @throws \App\Common\Exception\InvalidUserStateException
     */
    private function handle(User $user): void
    {
        $user->block();

        /**
         * @todo create user persistence
         */

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
