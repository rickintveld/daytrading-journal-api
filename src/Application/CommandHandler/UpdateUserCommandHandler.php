<?php

namespace App\Application\CommandHandler;

use App\Application\Command\UpdateUserCommand;
use App\Common\Contracts\CommandHandler;
use App\Common\Exception\UserNotFoundException;
use App\Domain\Contracts\Repository\UserRepository;
use App\Domain\Model\User;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;

class UpdateUserCommandHandler implements CommandHandler
{
    public function __construct(private UserRepository $userRepository, private LoggerInterface $logger)
    {
    }

    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
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
     * @throws ORMException
     * @throws OptimisticLockException
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
