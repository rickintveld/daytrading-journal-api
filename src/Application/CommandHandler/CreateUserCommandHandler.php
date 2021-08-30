<?php

namespace App\Application\CommandHandler;

use App\Application\Command\CreateUserCommand;
use App\Common\Exception\UserNotFoundException;
use App\Common\Interfaces\CommandHandler;
use App\Infrastructure\Entity\User;
use App\Infrastructure\Entity\UserSettings;
use App\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @package App\Application\CommandHandler
 */
class CreateUserCommandHandler implements CommandHandler
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
     * @param \App\Application\Command\CreateUserCommand $command
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function __invoke(CreateUserCommand $command): void
    {
        $user = $this->userRepository->findOneBy(['email' => $command->getEmail()]);

        if ($user) {
            throw new UserNotFoundException('User already exists');
        }

        $this->handle($command);
    }

    /**
     * @param \App\Application\Command\CreateUserCommand $command
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function handle(CreateUserCommand $command): void
    {
        $userSettings = new UserSettings();
        $userSettings->setCapital($command->getCapital());

        $newUser = new User();
        $newUser
            ->setFirstName($command->getFirstName())
            ->setLastName($command->getLastName())
            ->setEmail($command->getEmail())
            ->setPassword($command->getPassword())
            ->setUserSettings($userSettings)
            ->setBlocked(false)
            ->setRemoved(false);

        $this->entityManager->persist($newUser);
        $this->entityManager->flush();
    }
}
