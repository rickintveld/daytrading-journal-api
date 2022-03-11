<?php

namespace App\Application\CommandHandler;

use App\Application\Command\UnBlockUserCommand;
use App\Common\Contracts\CommandHandler;
use App\Common\Exception\InvalidUserStateException;
use App\Common\Exception\UserNotFoundException;
use App\Domain\Contracts\Repository\UserRepository;
use App\Domain\Model\User;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;

class UnblockUserCommandHandler implements CommandHandler
{
    public function __construct(private UserRepository $userRepository, private LoggerInterface $logger)
    {
    }

    /**
     * @throws InvalidUserStateException
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function __invoke(UnblockUserCommand $command): void
    {
        try {
            $user = $this->userRepository->findOneByIdentifier($command->getIdentifier());
            $this->handle($user);
        } catch (UserNotFoundException $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    /**
     * @throws InvalidUserStateException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function handle(User $user): void
    {
        $user->unblock();

        $this->userRepository->store($user);
    }
}
