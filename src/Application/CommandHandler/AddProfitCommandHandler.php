<?php

namespace App\Application\CommandHandler;

use App\Application\Command\AddProfitCommand;
use App\Common\Exception\UserNotFoundException;
use App\Common\Interfaces\CommandHandler;
use App\Infrastructure\Entity\Profit;
use App\Infrastructure\Entity\User;
use App\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @package App\Application\CommandHandler
 */
class AddProfitCommandHandler implements CommandHandler
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
     * @param \App\Application\Command\AddProfitCommand $command
     * @throws \Exception
     */
    public function __invoke(AddProfitCommand $command): void
    {
        $user = $this->userRepository->findOneBy(['id' => $command->getUserId()]);

        if (!$user) {
            throw new UserNotFoundException(sprintf('No user found for id %s', $command->getUserId()));
        }

        $this->handle($command, $user);
    }

    /**
     * @param \App\Application\Command\AddProfitCommand $command
     * @param \App\Infrastructure\Entity\User           $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function handle(AddProfitCommand $command, User $user): void
    {
        $profit = new Profit();
        $profit->setUser($user)
               ->setAmount($command->getProfit())
               ->setCreatedAt(new \DateTimeImmutable());

        $user->addProfit($profit);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
