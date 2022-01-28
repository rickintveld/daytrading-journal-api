<?php

namespace App\Application\CommandHandler;

use App\Application\Command\RestoreUserCommand;
use App\Common\Contracts\CommandHandler;
use App\Common\Exception\UserNotFoundException;
use App\Domain\Contracts\Repository\UserRepository;
use App\Domain\Model\User;
use Psr\Log\LoggerInterface;

/**
 * @package App\Application\CommandHandler
 */
class RestoreUserCommandHandler implements CommandHandler
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
     * @param \App\Application\Command\RestoreUserCommand $command
     * @throws \Exception
     */
    public function __invoke(RestoreUserCommand $command): void
    {
        try {
            $user = $this->userRepository->findOneByIdentifier($command->getIdentifier());
            $this->handle($user);
        } catch (UserNotFoundException $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    /**
     * @param \App\Domain\Model\User $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function handle(User $user): void
    {
        $user->restore();

        $this->userRepository->store($user);
    }
}
