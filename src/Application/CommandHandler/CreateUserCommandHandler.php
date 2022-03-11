<?php

namespace App\Application\CommandHandler;

use App\Application\Command\CreateUserCommand;
use App\Common\Contracts\CommandHandler;
use App\Common\Exception\UserAlreadyExists;
use App\Common\Exception\UserNotFoundException;
use App\Domain\Builder\UserBuilder;
use App\Domain\Contracts\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;

class CreateUserCommandHandler implements CommandHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private UserBuilder $userBuilder,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @throws UserAlreadyExists
     * @throws UserNotFoundException
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function __invoke(CreateUserCommand $command): void
    {
        $user = $this->userRepository->findOneByEmail($command->getEmail());

        if ($user) {
            $message = sprintf('User with e-mail %s already exists', $command->getEmail());
            $this->logger->error($message);
            throw new UserAlreadyExists($message);
        }

        $this->handle($command);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws \Exception
     */
    private function handle(CreateUserCommand $command): void
    {
        $this->userRepository->store($this->userBuilder->build($command->toArray()));
    }
}
