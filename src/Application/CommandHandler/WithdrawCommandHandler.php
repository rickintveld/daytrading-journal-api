<?php

namespace App\Application\CommandHandler;

use App\Application\Command\WithdrawCommand;
use App\Common\Exception\UserNotFoundException;
use App\Common\Interfaces\CommandHandler;
use App\Infrastructure\Entity\User;
use App\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @package App\Application\CommandHandler
 */
class WithdrawCommandHandler implements CommandHandler
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
     * @param \App\Application\Command\WithdrawCommand $command
     * @throws \Exception
     */
    public function __invoke(WithdrawCommand $command): void
    {
        $user = $this->userRepository->findOneBy(['id' => $command->getUserId()]);

        if (!$user) {
            throw new UserNotFoundException(sprintf('No user found for id %s', $command->getUserId()));
        }

        $this->handle($command, $user);
    }

    /**
     * @param \App\Application\Command\WithdrawCommand $command
     * @param \App\Infrastructure\Entity\User          $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \App\Common\Exception\InvalidFundsException
     */
    private function handle(WithdrawCommand $command, User $user): void
    {
        $user->withdraw($command->getAmount());

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
