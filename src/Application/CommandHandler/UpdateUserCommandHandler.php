<?php

namespace App\Application\CommandHandler;

use App\Application\Command\UpdateUserCommand;
use App\Common\Contracts\CommandHandler;
use App\Common\Exception\UserNotFoundException;
use App\Domain\Contracts\Repository\UserRepository;
use App\Domain\Model\User;
use Psr\Log\LoggerInterface;

/**
 * @package App\Application\CommandHandler
 */
class UpdateUserCommandHandler implements CommandHandler
{
    private UserRepository $userRepository;
    private LoggerInterface $logger;

    /**
     * @param \App\Domain\Contracts\Repository\UserRepository $userRepository
     * @param \Psr\Log\LoggerInterface                        $logger
     */
    public function __construct(UserRepository $userRepository, LoggerInterface $logger)
    {
        $this->userRepository = $userRepository;
        $this->logger = $logger;
    }

    /**
     * @param \App\Application\Command\UpdateUserCommand $command
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function __invoke(UpdateUserCommand $command): void
    {
        try {
            $user = $this->userRepository->findOneByIdentifier($command->getIdentifier());
            $this->handle($command, $user);
        } catch (UserNotFoundException $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    /**
     * @param \App\Application\Command\UpdateUserCommand $command
     * @param \App\Domain\Model\User                     $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
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

        $this->userRepository->store($user);
    }
}
